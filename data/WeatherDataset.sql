CREATE DATABASE IF NOT EXISTS WeatherDataset;

USE WeatherDataset;

CREATE TABLE IF NOT EXISTS Temperature(
	MyTime datetime,
    Wind int(4),
    LDT varbinary(6),
    Rain int(4),
    Temp float(3,1),
    Humidity float(3,1)
);

-- DROP TABLE Temperature;