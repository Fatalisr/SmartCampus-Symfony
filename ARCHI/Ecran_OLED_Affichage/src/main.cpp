#include <Arduino.h>
#include <U8g2lib.h>
#include <string>

using namespace std;

#ifdef U8X8_HAVE_HW_SPI
#include <SPI.h>
#endif
#ifdef U8X8_HAVE_HW_I2C
#include <Wire.h>
#endif

U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /* data=*/ SDA, /* reset=*/ U8X8_PIN_NONE); // Software I2C

/*
 * @brief Displays all the data collected by the sensors
 * @param x : coordinate x of the message
 * @param y : coordinate y of the message
 * @param dataType : message that will be displayed on the screen
 * @param value : value that will be displayed on the screen
 * @return : void
 */
void displayScreen(int x, int y, string dataType ,float value);

void setup(void)
{
  Serial.begin(9600);
  if(!screen.begin())
  {
    Serial.println("Erreur lors de l'initialisation de l'écran");
  }

  screen.setFont(u8g2_font_luBIS08_tf);   // Choix du fond d'écran

}
 
void loop(void)
{
  screen.clearBuffer();                           // Clears the buffer

  screen.drawStr(0,15,"Donnees");
  displayScreen(0,29,"CO2 : ", 412.0);            // Displays the CO2 collected by the SGP30
  displayScreen(0,38,"Temperature : ", 22.0);     // Displays the temperature collected by the DHT22
  displayScreen(0,47, "Humidite : ", 3.0);        // Displays the humidity collected by the DHT22
  displayScreen(0,56, "Luminosite : ", 500.0);    // Displays the CO2 collected by the LS06-MΦ
  displayScreen(0,65, "Horodatage : ", 500.0);    // Displays the time where the data above was collected

  screen.sendBuffer();                            // Envoie la mémoire interne à l'affichage
  delay(5000);  
}

void displayScreen(int x, int y, string dataType, float value)
{
  string stringValue = to_string(value);          // Transforming the float value in a string
  string stringData = dataType + stringValue;     // Fusionning the data type and the value converted to string
  const char * printedData = stringData.c_str();  // Converting the message from string to char * because drawStr only takes char* and no string
  screen.drawStr(x,y,printedData);                // Writing the message to the internal memory
}
