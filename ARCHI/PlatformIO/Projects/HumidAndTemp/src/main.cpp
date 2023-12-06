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

//Instence de l'ecran
U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /*
data=*/ SDA, /* reset=*/ U8X8_PIN_NONE);

//Port et type du capteur
#define DHT_PIN 10
#define DHT_TYPE DHT22
DHT humiTemp(DHT_PIN, DHT_TYPE); //Instance du capteur

void readHumidity();
void readTemperature();

void setup() {
  Serial.begin(9600);
  //-------------------------------------------------------------
  /* Lancement de l'écran */
  screen.begin();
  // Parametrage de la police (si on ne le fait pas rien ne s'affiche, a ne pas oublier)
  screen.setFont(u8g2_font_luBIS08_tf);
  //-------------------------------------------------------------
  // Demarrage
  humiTemp.begin();
}

void loop(){
  readHumidity();
  readTemperature();

  screen.sendBuffer();
  // Vide le buffer
  screen.clearBuffer();
  delay(3000);
}

void readHumidity(){
  // Reccuperation des valeurs
  float humidity = humiTemp.readHumidity();
  // Verification
  if (isnan(humidity)) {
      Serial.println("Erreur lors de la lecture du capteur DHT !");
      delay(1000);
  }
  char message[20];
  sprintf(message, "%f", humidity); //comme un printf mais dans une chaine !
  // Ecriture de texte dans le buffer
  int positionX = 5;
  int positionY = 30;
  screen.drawStr(positionX, positionY, message);
}

void readTemperature(){
  // Reccuperation des valeurs
  float temp = humiTemp.readTemperature();
  // Verification
  if (isnan(temp)) {
      Serial.println("Erreur lors de la lecture du capteur DHT !");
      delay(1000);
  }
  //------------------------------------------------------
  char message[20];
  sprintf(message, "%f", temp); //comme un printf mais dans une chaine !
  // Ecriture de texte dans le buffer
  int positionX = 5;
  int positionY = 10;
  screen.drawStr(positionX, positionY, message);
}