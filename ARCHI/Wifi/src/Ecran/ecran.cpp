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

#include "../API/gestion_api.h"
#include "../variables.h"

// Instance de l'écran
U8G2_SSD1306_128X64_NONAME_F_SW_I2C screen(U8G2_R0, /* clock=*/ SCL, /* data=*/ SDA, /* reset=*/ U8X8_PIN_NONE); // Software I2C


// Initialisation de l'écran
void init_screen(){
  if(!screen.begin()){
    Serial.println("Erreur lors de l'initialisation de l'écran");
  }
  screen.setFont(u8g2_font_luBIS08_tf);
}

// Affichage standard 
void displayScreen(int x, int y, String data){
  const char * printedData = data.c_str();
  screen.drawUTF8(x,y,printedData);
}

void displaySensorValue(int x, int y, String dataType, String dataUnit, float value)
{ 
  // Conversion de la valeur float en string pour l'affichage (precision au dixième)
  String stringValue = String(value);                            
  stringValue.remove(stringValue.length()-1);
  String stringData = dataType + " : " +stringValue+" "+dataUnit;   
  const char * printedData = stringData.c_str();                   
  screen.drawUTF8(x,y,printedData);                                
}

void loadingDisplay(int x, int y){
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

void displayValuesOnScreenTask(void* parameter){
  loadingDisplay(10, 40);
  for(;;){
    screen.clearBuffer();
    displayScreen(10, 10,getDate());
    displaySensorValue(10, 28, "Temperature", "°C", temperature);
    displaySensorValue(10, 40, "Humidité", "%", humidity);
    displaySensorValue(10, 52, "CO2", "ppm", ppm);
    screen.sendBuffer();
    vTaskDelay( pdMS_TO_TICKS( 2000 ) );
  }
}


