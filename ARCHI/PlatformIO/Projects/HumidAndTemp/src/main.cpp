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

const int thresholdvalue=20;         //The threshold for which the LED should turn on. 
float Rsensor; //Resistance of sensor

#define LED_COUNT 13
#define LED_PIN 18
WS2812FX ws2812fx = WS2812FX(LED_COUNT, LED_PIN, NEO_RGB + NEO_KHZ800);

void readLuminosity();

void setup() {
  Serial.begin(9600);
  //-------------------------------------------------------------
  /* Lancement de l'écran */
  screen.begin();
  // Parametrage de la police (si on ne le fait pas rien ne s'affiche, a ne pas oublier)
  screen.setFont(u8g2_font_luBIS08_tf);
  //-------------------------------------------------------------
  ws2812fx.init();
  ws2812fx.setBrightness(25);
  ws2812fx.setColor(0x00, 0x00, 0xff);
  ws2812fx.setMode(FX_MODE_STATIC);
  ws2812fx.start();
  ws2812fx.service();
}

void loop(){
  readLuminosity();

  screen.sendBuffer();
  // Vide le buffer
  screen.clearBuffer();
  delay(3000);
}

void readLuminosity(){
  int sensorValue = analogRead(3); 
  Rsensor=(float)(8191-sensorValue)*10/sensorValue;
  if(Rsensor>thresholdvalue)
  {
      ws2812fx.setBrightness(250);
      ws2812fx.setColor(0x00, 0xff, 0x00);
  }
  else
  {
      ws2812fx.setBrightness(25);
      ws2812fx.setColor(0xff, 0x00, 0x00);
  }
  ws2812fx.service();
  Serial.println("the sensor resistance is ");
  Serial.println(Rsensor,DEC);//show the light intensity on the serial monitor;
  char message[20];
  sprintf(message, "%f", Rsensor);
  int positionX = 5;
  int positionY = 50;
  screen.drawStr(positionX, positionY, message);
}