-- fsm.vhd: Finite State Machine
-- Author(s): Filip Pospíšil (xpospi0f)
--
library ieee;
use ieee.std_logic_1164.all;
-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity fsm is
port(
   CLK         : in  std_logic;
   RESET       : in  std_logic;

   -- Input signals
   KEY         : in  std_logic_vector(15 downto 0);
   CNT_OF      : in  std_logic;

   -- Output signals
   FSM_CNT_CE  : out std_logic;
   FSM_MX_MEM  : out std_logic;
   FSM_MX_LCD  : out std_logic;
   FSM_LCD_WR  : out std_logic;
   FSM_LCD_CLR : out std_logic
);
end entity fsm;

-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of fsm is
   type t_state is (TEST1, TEST2, TEST3, TEST4, TEST5, TEST1_1, TEST1_2, TEST1_3, TEST1_4, TEST1_5, TEST1_6, TEST1_7, TEST2_1, TEST2_2, TEST2_3, TEST2_4, TEST2_5, TEST2_6, FAIL, ACCESS_FAIL, ACCESS_OK, FINISH);
   signal present_state, next_state : t_state;

begin
-- -------------------------------------------------------
sync_logic : process(RESET, CLK)
begin
   if (RESET = '1') then
      present_state <= TEST1;
   elsif (CLK'event AND CLK = '1') then
      present_state <= next_state;
   end if;
end process sync_logic;

-- -------------------------------------------------------
next_state_logic : process(present_state, KEY, CNT_OF)
begin
   case (present_state) is

   when TEST1 =>
      if (KEY(2) = '1') then
         next_state <= TEST2;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1;
      end if;

   when TEST2 =>
      if (KEY(8) = '1') then
         next_state <= TEST3;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2;
      end if;

   when TEST3 =>
      if (KEY(3) = '1') then
         next_state <= TEST4;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST3;
      end if;

   when TEST4 =>
      if (KEY(0) = '1') then
         next_state <= TEST5;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST4;
      end if;

   when TEST5 =>
      if (KEY(3) = '1') then
         next_state <= TEST1_1;
      elsif (KEY(8) = '1') then
        next_state <= TEST2_1;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST5;
      end if;

   when TEST1_1 =>
      if (KEY(6) = '1') then
         next_state <= TEST1_2;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_1;
      end if;

   when TEST1_2 =>
      if (KEY(6) = '1') then
         next_state <= TEST1_3;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_2;
      end if;

   when TEST1_3 =>
      if (KEY(4) = '1') then
         next_state <= TEST1_4;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_3;
      end if;

   when TEST1_4 =>
      if (KEY(6) = '1') then
         next_state <= TEST1_5;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_4;
      end if;

   when TEST1_5 =>
      if (KEY(4) = '1') then
         next_state <= TEST1_6;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_5;
      end if;

   when TEST1_6 =>
      if (KEY(0) = '1') then
         next_state <= TEST1_7;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_6;
      end if;

   when TEST1_7 =>
      if (KEY(15) = '1') then
         next_state <= ACCESS_OK;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST1_7;
      end if;

   when TEST2_1 =>
      if (KEY(5) = '1') then
         next_state <= TEST2_2;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_1;
      end if;

   when TEST2_2 =>
      if (KEY(1) = '1') then
         next_state <= TEST2_3;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_2;
      end if;

   when TEST2_3 =>
      if (KEY(6) = '1') then
         next_state <= TEST2_4;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_3;
      end if;

   when TEST2_4 =>
      if (KEY(2) = '1') then
         next_state <= TEST2_5;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_4;
      end if;

   when TEST2_5 =>
      if (KEY(6) = '1') then
         next_state <= TEST2_6;
      elsif (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_5;
      end if;

   when TEST2_6 =>
      if (KEY(15) = '1') then
         next_state <= ACCESS_OK;
      elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= FAIL;
      else
         next_state <= TEST2_6;
      end if;

   when FAIL =>
      next_state <= FAIL;
      if (KEY(15) = '1') then
         next_state <= ACCESS_FAIL;
      end if;

   when ACCESS_FAIL =>
      next_state <= ACCESS_FAIL;
      if (CNT_OF = '1') then
         next_state <= FINISH;
      end if;

   when ACCESS_OK =>
      next_state <= ACCESS_OK;
      if (CNT_OF = '1') then
         next_state <= FINISH;
      end if;

   when FINISH =>
      next_state <= FINISH;
      if (KEY(15) = '1') then
         next_state <= TEST1;
      end if;

   when others =>
      null;

   end case;
end process next_state_logic;

-- -------------------------------------------------------
output_logic : process(present_state, KEY)
begin
   FSM_CNT_CE     <= '0';
   FSM_MX_MEM     <= '0';
   FSM_MX_LCD     <= '0';
   FSM_LCD_WR     <= '0';
   FSM_LCD_CLR    <= '0';

   case (present_state) is
   -- - - - - - - - - - - - - - - - - - - - - - -
   when ACCESS_FAIL =>
       FSM_CNT_CE <= '1';
       FSM_MX_LCD <= '1';
       FSM_MX_MEM <= '0';
       FSM_LCD_WR <= '1';

   -- - - - - - - - - - - - - - - - - - - - - - -
   when ACCESS_OK =>
       FSM_CNT_CE <= '1';
       FSM_MX_LCD <= '1';
       FSM_MX_MEM <= '1';
       FSM_LCD_WR <= '1';
   -- - - - - - - - - - - - - - - - - - - - - - -
   when FINISH =>
      if (KEY(15) = '1') then
         FSM_LCD_CLR    <= '1';
      end if;
   -- - - - - - - - - - - - - - - - - - - - - - -
   when others =>
     if (KEY(15) = '1') then
        FSM_LCD_CLR <= '1';
     elsif (KEY(14 downto 0) /= "000000000000000") then
        FSM_LCD_WR <= '1';
     end if;
   end case;
end process output_logic;

end architecture behavioral;
