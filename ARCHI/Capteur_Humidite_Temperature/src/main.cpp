#include <Arduino.h>
#include <U8g2lib.h>
#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif
#include "DHTesp.h"

/*
 * Initialisation of the global variables
*/

//Screen
U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /*data=*/ SDA, /* reset=*/ U8X8_PIN_NONE);

DHTesp dht;

/** Pin number for DHT22 data pin */
int dhtPin = 17;

String Temp;
String Humid;

bool getTemperature();
bool initTemp();

/*
 * @brief Setup of the sensor
*/
void setup() {
  Serial.begin(9600);
  screen.begin();
  screen.setFont(u8g2_font_luBIS08_tf);

  Serial.println();
  Serial.println("DHT ESP32 example with tasks");

  initTemp();
}

/*
 * @brief Loop where the sensor will be stuck in
*/
void loop(){
  if(getTemperature())
  {
    screen.drawStr(10, 10, Temp.c_str());
    screen.drawStr(10, 30, Humid.c_str());
  }

  screen.sendBuffer(); //Sending the buffer to the screen
  screen.clearBuffer(); //Clearing the buffer

  delay(3000); //3 second delay
}

/**
 * initTemp
 * Setup DHT library
 * Setup task and timer for repeated measurement
 * @return bool
 *    true if DHT22 is started
 *    false if DHT22 couldn't be started
 */
bool initTemp() {
  // Initialize temperature sensor
	dht.setup(dhtPin, DHTesp::DHT22);
  if (dht.getStatus() != 0) {
		Serial.println("DHT22 error status: " + String(dht.getStatusString()));
		return false;
  }
	Serial.println("DHT22 initiated");

  return true;
}

/**
 * getTemperature
 * Reads temperature from DHT22 sensor
 * @return bool
 *    true if temperature could be aquired
 *    false if aquisition failed
*/
bool getTemperature() {
	// Reading temperature for humidity takes about 250 milliseconds!
	// Sensor readings may also be up to 2 seconds 'old' (it's a very slow sensor)
  TempAndHumidity newValues = dht.getTempAndHumidity();

	// Check if any reads failed and exit early (to try again).
	if (dht.getStatus() != 0) {
		Serial.println("DHT22 error status: " + String(dht.getStatusString()));
		return false;
  }

  Temp = "Temperature : " + String(newValues.temperature);
  Humid = "Humidite : " + String(newValues.humidity);
  Temp.remove(Temp.length()-1);
  Humid.remove(Humid.length()-1);
	return true;
}