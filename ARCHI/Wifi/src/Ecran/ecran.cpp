/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/
#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP32Time.h>
#include "sensirion_common.h"
#include "sgp30.h"
#include "esp_wpa2.h" //Librairie wpa2 pour la connexion au réseaux d'enterprise
#include <Adafruit_Sensor.h>
#include <DHT_U.h>
#include <U8g2lib.h>
#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif

// Instance de l'écran
U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /* data=*/ SDA, /* reset=*/ U8X8_PIN_NONE); // Software I2C


// accès aux fonction clearBuffer et sendBuffer pour la gestion de l'affichage dans les tasks pour chaque capteur
void clearB()
{
  screen.clearBuffer();
}

void sendB()
{
  screen.sendBuffer();
}

// Initialisation de l'écran
void initScreen()
{
  if(!screen.begin())
  {
    Serial.println("Erreur lors de l'initialisation de l'écran");
  }
  screen.setFont(u8g2_font_luBIS08_tf);
  Serial.println("Init Screen OK");
}

void loadingDisplay()
{
    int x = 5;
    int y = 40;
    screen.clearBuffer();
    screen.drawUTF8(x,y,"Chargement");
    screen.sendBuffer();
    delay(500);
    screen.clearBuffer();
    screen.drawUTF8(x,y,"Chargement .");
    screen.sendBuffer();
    delay(500);
    screen.clearBuffer();
    screen.drawUTF8(x,y,"Chargement ..");
    screen.sendBuffer();
    delay(500);
    screen.clearBuffer();
    screen.drawUTF8(x,y,"Chargement ...");
    screen.sendBuffer();
    delay(500);
}