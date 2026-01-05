CREATE DATABASE IF NOT EXISTS WeatherDataset;

USE WeatherDataset;

CREATE TABLE IF NOT EXISTS Temperature(
	MyTime datetime,
	Wind int(4),
	LDR varbinary(6),
	Rain int(4),
	Temp float(3,1),
	Humidity float(3,1)
);

CREATE TABLE IF NOT EXISTS IOTControl(
	Control varchar(1),
    RGBLED varchar(5),
    AIpred varchar(10)
);
Insert INTO IOTControl
VALUES ("0", "RED", "Not Play");
