#include <Arduino.h>
#include <U8g2lib.h>
#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif
#include <DHT.h> //HUMI-TEMP
#include <Adafruit_Sensor.h>

/*
 * Initialisation of the global variables
*/

//Screen
U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /*data=*/ SDA, /* reset=*/ U8X8_PIN_NONE);

#define DHT_PIN 10
#define DHT_TYPE DHT22
DHT humiTemp(DHT_PIN, DHT_TYPE); //Temperature and Humidity Sensor

/*
 * @brief Read the Humidity value from the sensor
 * @return the humidity detected. Betwenn 5% and 99%.
 */
float get_Humidity_value();

/*
 * @brief Read the Temperature value from the sensor
 * @return the temperature detected. Betwenn -40°C and 80°C.
 */
float get_Temperature_value();

/*
 * @brief display on screen
 * Add the given value to the screen buffer at the given coordinates
 */
void print_Screen(int x, int y, float value);

/*
 * @brief Setup of the sensor
*/
void setup() {
  Serial.begin(9600);
  screen.begin();
  screen.setFont(u8g2_font_luBIS08_tf);
  if(!humiTemp.begin()){ //Verify if the connection has been established
    Serial.println("Erreur lors du demarrage du capteur !");
  }
}

/*
 * @brief Loop where the sensor will be stuck in
*/
void loop(){
  float Temp, Humid;
  Temp = get_Temperature_value();
  Humid = get_Humidity_value();

  print_Screen(5, 10, Temp);
  print_Screen(5, 30, Humid);

  screen.sendBuffer(); //Sending the buffer to the screen

  screen.clearBuffer(); //Clearing the buffer

  delay(3000); //3 second delay
}

void get_Humidity_value(){
  float humidity = humiTemp.readHumidity();
  if (isnan(humidity)) { //Verify if a value has been returned
      Serial.println("Erreur lors de la lecture du capteur DHT !");
      delay(1000);
  }
}

void get_Temperature_value(){
  float temp = humiTemp.readTemperature();
  if (isnan(temp)) { //Verify if a value has been returned
      Serial.println("Erreur lors de la lecture du capteur DHT !");
      delay(1000);
  }
}

void print_Screen(int x, int y, float value){
  char message[20];
  sprintf(message, "%f", value); //Changing float into string and affecting it onto message
  screen.drawStr(x, y, message); //Adding to the screen buffer
}
