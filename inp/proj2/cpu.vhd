-- cpu.vhd: Simple 8-bit CPU (BrainF*ck interpreter)
-- Copyright (C) 2020 Brno University of Technology,
--                    Faculty of Information Technology
-- Author(s): DOPLNIT
--

library ieee;
use ieee.std_logic_1164.all;
use ieee.std_logic_arith.all;
use ieee.std_logic_unsigned.all;

-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity cpu is
 port (
   CLK   : in std_logic;  -- hodinovy signal
   RESET : in std_logic;  -- asynchronni reset procesoru
   EN    : in std_logic;  -- povoleni cinnosti procesoru
 
   -- synchronni pamet ROM
   CODE_ADDR : out std_logic_vector(11 downto 0); -- adresa do pameti
   CODE_DATA : in std_logic_vector(7 downto 0);   -- CODE_DATA <- rom[CODE_ADDR] pokud CODE_EN='1'
   CODE_EN   : out std_logic;                     -- povoleni cinnosti
   
   -- synchronni pamet RAM
   DATA_ADDR  : out std_logic_vector(9 downto 0); -- adresa do pameti
   DATA_WDATA : out std_logic_vector(7 downto 0); -- ram[DATA_ADDR] <- DATA_WDATA pokud DATA_EN='1'
   DATA_RDATA : in std_logic_vector(7 downto 0);  -- DATA_RDATA <- ram[DATA_ADDR] pokud DATA_EN='1'
   DATA_WE    : out std_logic;                    -- cteni (0) / zapis (1)
   DATA_EN    : out std_logic;                    -- povoleni cinnosti 
   
   -- vstupni port
   IN_DATA   : in std_logic_vector(7 downto 0);   -- IN_DATA <- stav klavesnice pokud IN_VLD='1' a IN_REQ='1'
   IN_VLD    : in std_logic;                      -- data platna
   IN_REQ    : out std_logic;                     -- pozadavek na vstup data
   
   -- vystupni port
   OUT_DATA : out  std_logic_vector(7 downto 0);  -- zapisovana data
   OUT_BUSY : in std_logic;                       -- LCD je zaneprazdnen (1), nelze zapisovat
   OUT_WE   : out std_logic                       -- LCD <- OUT_DATA pokud OUT_WE='1' a OUT_BUSY='0'
 );
end cpu;


-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of cpu is

 -- zde dopiste potrebne deklarace signalu


 -- program counter --
	signal pc_reg: std_logic_vector (11 downto 0);
	signal pc_inc: std_logic;
	signal pc_dec: std_logic;
	signal pc_ld : std_logic;


 -- pointer --
	signal ptr_reg: std_logic_vector (9 downto 0);
	signal ptr_inc: std_logic;
	signal ptr_dec: std_logic;

	signal ras_reg: std_logic_vector(11 downto 0);
	signal ras_pop: std_logic;
	signal ras_push: std_logic;


-- states --
	type fsm_state is (
		s_start,
		s_fetch,
		s_decode,

		S_program_inc,
		s_program_load_inc,
		s_program_load_end_inc,

		s_program_dec,
		s_program_load_dec,
		s_program_load_end_dec,
		
		s_pointer_inc,
		s_pointer_dec,

		s_while_start,
		s_while_loop,
		S_while_check,
		s_while_en,
		s_while_end,

		s_write,
		s_write_start,

		s_get,
		s_get_start,

		s_null
	);
	signal state : fsm_state := s_start;
	signal nState : fsm_state;

-- mx --
	signal mx_select : std_logic_vector (1 downto 0) := (others => '0');
	signal mx_output : std_logic_vector (7 downto 0) := (others => '0');

begin

 -- zde dopiste vlastni VHDL kod


 -- pri tvorbe kodu reflektujte rady ze cviceni INP, zejmena mejte na pameti, ze 
 --   - nelze z vice procesu ovladat stejny signal,
 --   - je vhodne mit jeden proces pro popis jedne hardwarove komponenty, protoze pak
 --   - u synchronnich komponent obsahuje sensitivity list pouze CLK a RESET a 
 --   - u kombinacnich komponent obsahuje sensitivity list vsechny ctene signaly.

--pc 
	pc: process (CLK, RESET, pc_reg, pc_inc, pc_dec, pc_ld) is
	begin
		if RESET = '1' then
			pc_reg <= "000000000000";
		elsif rising_edge(CLK) then
			if pc_inc = '1' then
				pc_reg <= (pc_reg + 1);
			elsif pc_dec = '1' then
				pc_reg <= (pc_reg - 1);
			end if;
		end if;
		CODE_ADDR <= pc_reg;
	end process;
	
--/pc

--ptr
	ptr: process (CLK, RESET, ptr_inc, ptr_dec) is
	begin
		if RESET = '1' then
		ptr_reg <= "0000000000";
		elsif rising_edge(CLK) then
			if ptr_inc = '1' then
				ptr_reg <= (ptr_reg + 1);
			elsif ptr_dec = '1' then
				ptr_reg <= (ptr_reg - 1);
			end if;
		end if;
	DATA_ADDR <= ptr_reg;
	end process;
	
--/ptr

--mx
	mx: process (CLK, RESET, mx_select) is
	begin
		if RESET = '1' then 
			mx_output <= (others => '0');
		elsif rising_edge(CLK) then
			case mx_select is 
				when "00" =>
					mx_output <= IN_DATA;
				when "01" =>
					mx_output <= DATA_RDATA + 1;
				when "10" =>
					mx_output <= DATA_RDATA - 1;
				when others =>
					mx_output <= (others => '0');
			end case;
		end if;

	end process;
	DATA_WDATA <= mx_output;
--/mx

--fsm
	state_logic: process (CLK, RESET, EN) is
	begin
		if RESET = '1' then
			state <= s_start;
		elsif rising_edge(CLK) then 
			if EN = '1' then
				state <= nState;
			end if;
		end if;	
	end process;

	next_state_logic: process(state, OUT_BUSY, IN_VLD, CODE_DATA, DATA_RDATA) is

	begin

		-- inicializace
		pc_inc <= '0';
		pc_dec <= '0';
		pc_ld <= '0';

		ptr_inc <= '0';
		ptr_dec <= '0';

		ras_pop <= '0';
		ras_push <= '0';
		

		CODE_EN <= '1';
		DATA_EN <= '0';
		--DATA_WE <= '0';
		--IN_REQ <= '0';
		OUT_WE <= '0';

		mx_select <= "00";


		case state is
			when s_start =>
				nState <= s_fetch;

			when s_fetch =>
				CODE_EN <= '1';
				nState <= s_decode;

			when s_decode =>
				case CODE_DATA is
					when X"3E" =>
						nState <= s_pointer_inc;
					when X"3C" =>
						nState <= s_pointer_dec;

					when X"2B" =>
						nState <= s_program_inc;
					when X"2D" =>
						nState <= s_program_dec;

					when X"5B" =>
						nState <= s_while_start;
					when X"5D" =>
						nState <= s_while_end;

					when X"2E" =>
						nState <= s_write;
					when X"2C" =>
						nState <= s_get;

					when X"00" =>
						nState <= s_null;
					when others =>
						pc_inc <= '1';
						nState <= s_decode;
				end case;
			
			--ok	
			when s_pointer_inc =>
				pc_inc <= '1';
				ptr_inc <= '1';
				nState <= s_fetch;
				
			--ok
			when s_pointer_dec =>
				pc_inc <= '1';
				ptr_dec <= '1';
				nState <= s_fetch;



			--ok
			when s_program_inc =>
				--DATA_ADDR <= ptr_reg;
				DATA_EN <= '1';
				DATA_WE <= '0';
				nState <= s_program_load_inc;
		
			when s_program_load_inc =>
				mx_select <= "01";
				nState <= s_program_load_end_inc;

			when s_program_load_end_inc =>
				--DATA_ADDR <= ptr_reg;
				DATA_EN <= '1';
				DATA_WE <= '1';
				pc_inc <=  '1';
				nState <= s_fetch;
				
			--ok
			when s_program_dec =>
				--DATA_ADDR <= ptr_reg;
				DATA_EN <= '1';
				DATA_WE <= '0';
				nState <= s_program_load_dec;
		
			when s_program_load_dec =>
				mx_select <= "10";
				nState <= s_program_load_end_dec;

			when s_program_load_end_dec =>
				--DATA_ADDR <= ptr_reg;
				DATA_EN <= '1';
				DATA_WE <= '1';
				pc_inc <=  '1';
				nState <= s_fetch;




			--weird
			when s_while_start =>
			--	CODE_EN <= '1';
				pc_inc <= '1';
				DATA_EN <= '1';
				DATA_WE <= '0';
				nState <= s_while_check;
			
			when s_while_check =>
				ras_push <= '1';
				--pc_inc <= '1';
				if DATA_RDATA /= "00000000" then
					nState <= s_fetch;
				else
					CODE_EN <= '1';
					nState <= s_while_loop;
				end if;




			
			when s_while_loop =>
				pc_inc <= '1';
				if CODE_DATA = X"5D" then
					ras_pop <= '1';
					ras_reg <= "000000000000";					
					nState <= s_fetch;
				else
					nState <= s_while_en;
				end if;

			when s_while_en =>
			--	pc_inc <= '1';
				DATA_EN <= '1';
				nState <= s_while_loop;	

			when s_while_end =>
				if DATA_RDATA /= "00000000" then
					nState <= s_fetch;
					pc_ld <= '1';
					pc_inc <= '1';
				else
					ras_pop <= '1';
					pc_inc <= '1';
					nState <= s_fetch;
				end if;





			when s_write =>
				if OUT_BUSY = '1' then
					nState <= s_write;
				else
					DATA_EN <= '1';
					DATA_WE <= '0';
					nState <= s_write_start;
				end if;

			when s_write_start =>
				OUT_WE <= '1';
				OUT_DATA <= DATA_RDATA;
				pc_inc <= '1';
				nState <= s_fetch;

		
			
			when s_get =>
				IN_REQ <= '1';
				mx_select <= "00";
				nState <= s_get_start;

			when s_get_start =>
		--		if IN_VLD /= '1'  then
				--	IN_REQ <= '1';
				--	mx_select <= "00";
			--		nState <= s_get_start;
			--	else
			--		DATA_EN <= '1';
					DATA_WE <= '1';
		--		pc_inc <= '1';
			--		nState <= s_fetch;
			--	end if;



			when s_null =>
				nState <= s_null;

			when others =>


		end case;
	end process;
--/fsm	
end behavioral;
 

