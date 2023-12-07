#include <Arduino.h>
#include <U8g2lib.h>
#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif
#include <Adafruit_Sensor.h>
#include <math.h>
#include <WS2812FX.h>

/*
 * Initialisation of the global variables
*/

const int thresholdvalue=20;//The threshold for which the LED should turn on. 
float Rsensor;// Quantity of light in the room

#define LED_COUNT 13
#define LED_PIN 18
WS2812FX ws2812fx = WS2812FX(LED_COUNT, LED_PIN, NEO_RGB + NEO_KHZ800); //Light Sensor

/*
 * @brief Initializes the LED
 * Define its base color and brightness and start it
 */
void init_LED();

/*
 * @brief Read the value from the sensor
 * @return the quantity of Lux after calculation. Betwenn 0 and 30(?).
 */
float get_Luminosity_value();

/*
 * @brief display on screen
 * Add the given value to the screen buffer at the given coordinates
 */
void print_Screen(int, int, float);

/*
 * @brief Setup of the sensor
*/
void setup() {
  Serial.begin(9600);
  screen.begin();
  screen.setFont(u8g2_font_luBIS08_tf);
  init_LED();
}

/*
 * @brief Loop where the sensor will be stuck in
*/
void loop(){
  float LuxCalc;
  LuxCalc = get_Luminosity_value();
  if(LuxCalc>thresholdvalue) //Set different color and brightness depending on the value of LuxCalc
  {
      ws2812fx.setBrightness(250);
      ws2812fx.setColor(0x00, 0xff, 0x00); //Red Color
  }
  else
  {
      ws2812fx.setBrightness(25);
      ws2812fx.setColor(0xff, 0x00, 0x00); //Green Color
  }
  ws2812fx.service();

  //Serial.println("the sensor resistance is ");
  //Serial.println(Lux,DEC);//show the light intensity on the serial monitor;
  print_Screen(5, 50, LuxCalc);

  screen.sendBuffer(); //Sending the buffer to the screen

  screen.clearBuffer(); //Clearing the buffer

  delay(3000); //3 second delay
}

void init_LED(){
  ws2812fx.init();
  ws2812fx.setBrightness(25);
  ws2812fx.setColor(0x00, 0x00, 0xff); // Blue color
  ws2812fx.setMode(FX_MODE_STATIC);
  ws2812fx.start();
  ws2812fx.service();
}

float get_Luminosity_value(){
  int sensorValue = analogRead(3); //Getting the electric output where the sensor is connected (here input 3)
  Rsensor=(float)(8191-sensorValue)*10/sensorValue; //Interpretation of the electric value
  return Rsensor;
}

void print_Screen(int x, int y, float value){
  char message[20];
  sprintf(message, "%f", value); //Changing float into string and affecting it onto message
  screen.drawStr(x, y, message); //Adding to the screen buffer
}