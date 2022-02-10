-- Author: Filip Pospisil (xpospi0f)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `carinfo`
--

--
-- Vypisuji data pro tabulku `brand`
--

INSERT INTO `brand` (`id`, `name`, `country`) VALUES
(1, 'Volkswagen', 'DE'),
(2, 'Skoda', 'CZ'),
(3, 'Audi', 'DE'),
(4, 'BMW', 'DE'),
(5, 'Peugeot', 'FR'),
(6, 'Volvo', 'SE');

--
-- Vypisuji data pro tabulku `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Regular'),
(2, 'Repair'),
(3, 'Tires'),
(4, 'Filters'),
(5, 'Engine'),
(6, 'Brakes'),
(7, 'Electronics'),
(8, 'Transmission'),
(9, 'Fluids'),
(10, 'Drivetrain'),
(11, 'Bodywork'),
(12, 'Safety features'),
(18, 'Air Conditioning');

--
-- Vypisuji data pro tabulku `color`
--

INSERT INTO `color` (`id`, `name`) VALUES
(1, 'Black'),
(2, 'White'),
(3, 'Red'),
(4, 'Blue'),
(5, 'Pink'),
(6, 'Silver'),
(9, 'Yellow'),
(10, 'Grey'),
(11, 'Orange'),
(12, 'Brown');

--
-- Vypisuji data pro tabulku `engine`
--

INSERT INTO `engine` (`id`, `code`, `cylinders`, `capacity`, `power`) VALUES
(1, 'CBAB', 4, 1968, 110),
(2, 'CBBB', 4, 1968, 135),
(3, 'DFHA', 4, 1968, 140),
(4, 'LCI', 6, 2993, 173),
(5, '2,4D5', 5, 2401, 129),
(6, 'BMP', 4, 1986, 103);

--
-- Vypisuji data pro tabulku `gas_station`
--

INSERT INTO `gas_station` (`id`, `name`) VALUES
(1, 'OMV'),
(2, 'Prim'),
(3, 'Silmet'),
(4, 'Shell'),
(5, 'ONO'),
(6, 'Benzina'),
(7, 'Unicorn'),
(8, 'EuroOil'),
(9, 'Agrotec');

--
-- Vypisuji data pro tabulku `model`
--

INSERT INTO `model` (`id`, `brand_id`, `name`) VALUES
(1, 1, 'Passat'),
(2, 2, 'Superb'),
(3, 3, 'A3'),
(4, 3, 'A4'),
(5, 3, 'A5'),
(6, 3, 'A6'),
(7, 3, 'A7'),
(8, 3, 'A8'),
(9, 3, 'Q3'),
(10, 3, 'Q5'),
(11, 3, 'Q7'),
(12, 4, '320i'),
(13, 4, '320d'),
(14, 4, '330i'),
(15, 4, '330d'),
(16, 4, '528i'),
(17, 4, '530i'),
(18, 4, '530d'),
(19, 4, 'X3'),
(20, 4, 'X5'),
(21, 4, 'X7'),
(22, 5, '206'),
(23, 5, '207'),
(24, 5, '208'),
(25, 5, '2008'),
(26, 5, '306'),
(27, 5, '406'),
(28, 5, '508'),
(29, 2, 'Octavia'),
(30, 2, 'Fabia'),
(31, 2, 'Kodiaq'),
(32, 2, 'Karoq'),
(33, 1, 'Golf'),
(34, 1, 'Polo'),
(35, 1, 'Caddy'),
(36, 1, 'Transporter'),
(37, 1, 'Arteon'),
(38, 1, 'Tiguan'),
(39, 1, 'Touareg'),
(40, 6, 'XC40'),
(41, 6, 'XC60'),
(42, 3, 'XC90'),
(43, 6, 'XC70'),
(44, 6, 'S90');

--
-- Vypisuji data pro tabulku `service_operation`
--

INSERT INTO `service_operation` (`id`, `category_id`, `name`, `description`, `interval_km`, `interval_time`) VALUES
(1, 9, 'Engine oil - change', 'Regular oil change.', 15000, 55),
(3, 8, 'Automatic Transmission Oil Change.', 'Automatic Transmission Oil Change.', 60000, NULL),
(4, 11, 'Window change - front', 'Window change - front', NULL, NULL),
(5, 11, 'Window change - rear', 'Window change - rear', NULL, NULL),
(6, 3, 'Tire change - all wheels', 'Changing tires on all wheels.', NULL, NULL),
(7, 3, 'Tire change - front axle', 'Changing tires only on front axle.', NULL, NULL),
(8, 3, 'Tire change - rear axle', 'Changing tires only on rear axle.', NULL, NULL),
(9, 3, 'Tire puncture - repair', 'Repairing only puncture without changing tire.', NULL, NULL),
(11, 4, 'Oil filter - change', 'Oil filter should be changed with every oil change.', 15000, 55),
(12, 4, 'Air filter - change', NULL, 15000, 55),
(13, 4, 'Fuel filter - change', NULL, 15000, 55),
(14, 9, 'Brake fluid - change.', NULL, 15000, 55),
(15, 6, 'Brake pads - change.', NULL, 20000, NULL),
(16, 6, 'Brake rotors - change.', NULL, 100000, NULL);

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `surname`, `birth_date`, `sex`) VALUES
(1, 'user1@carinfo.cz', '[]', '$2y$13$bWHWgIIlPI5zUYDZ1Vhehe.kcEJUScszIcsDOWNweQ.H/wAjxhZGa', 'Uzivatel', 'Prvni', '2000-02-01', 'male');

--
-- Vypisuji data pro tabulku `vehicle`
--

INSERT INTO `vehicle` (`id`, `brand_id`, `color_id`, `model_id`, `engine_id`, `owner_id`, `spz`, `vin`, `nickname`, `year`, `fuel`, `transmition`, `odometer`, `wheel_drive`, `stk`) VALUES
(4, 2, 2, 2, 3, 1, '2BU1742', 'TMBJJ7NP1H7020969', 'Sup', 2017, 'diesel', 'manual', 189731, 'FWD', '2022-10-10'),
(5, 4, 1, 18, 4, 1, '5SK6641', 'WBAAA1305H8251545', 'Bawko', 2010, 'diesel', 'automatic', 384207, 'RWD', '2022-12-12'),
(6, 6, 6, 41, 5, 1, '5Z76241', 'YV1DZ7241A2053204', 'Mazel', 2009, 'diesel', 'manual', 242871, 'AWD', '2023-01-10');

--
-- Vypisuji data pro tabulku `service_record`
--

INSERT INTO `service_record` (`id`, `operation_id`, `vehicle_id`, `date`, `mileage`, `note`, `price`) VALUES
(9, 1, 4, '2021-06-27', 185321, 'Shell Helix 5W-30', 2500),
(10, 13, 4, '2021-06-27', 185321, NULL, 350),
(11, 12, 4, '2021-06-27', 185321, NULL, 275),
(12, 3, 4, '2021-02-14', 176207, 'DSG VW original oil.', 13500),
(13, 1, 5, '2021-10-07', 383105, NULL, 1975),
(14, 14, 6, '2021-04-07', 233521, NULL, 1750),
(15, 15, 6, '2021-09-10', 242378, NULL, 9500),
(16, 16, 6, '2021-09-10', 242378, NULL, 16500);

--
-- Vypisuji data pro tabulku `fuel_record`
--

INSERT INTO `fuel_record` (`id`, `gas_station_id`, `vehicle_id`, `amount`, `price`, `mileage`, `date`) VALUES
(11, 6, 4, 68.33, 2459, 186666, '2021-08-01'),
(12, 1, 4, 34.78, 1270, 187542, '2021-08-27'),
(13, 4, 4, 67.21, 2413, 187994, '2021-09-13'),
(14, 6, 4, 66.3, 2314, 188746, '2021-09-30'),
(15, 5, 4, 14.21, 505, 189521, '2021-10-24'),
(16, 4, 4, 56.31, 2010, 189731, '2021-11-01'),
(17, 2, 5, 40.21, 1540, 381475, '2021-08-08'),
(18, 5, 5, 59.71, 2257, 381872, '2021-09-09'),
(19, 6, 5, 44.72, 1650, 382444, '2021-10-10'),
(20, 8, 5, 37.21, 1374, 383891, '2021-11-11'),
(21, 7, 5, 42.71, 1580, 384207, '2021-12-04'),
(22, 9, 6, 62.21, 2170, 241746, '2021-08-07'),
(23, 6, 6, 52.21, 1872, 242378, '2021-09-08'),
(24, 9, 6, 66.21, 2384, 242871, '2021-10-09');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
