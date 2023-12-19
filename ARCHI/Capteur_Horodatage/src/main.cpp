#include <Arduino.h>

// CONNECTIONS:
// DS1307 SDA --> SDA
// DS1307 SCL --> SCL
// DS1307 VCC --> 5v
// DS1307 GND --> GND


//#include <Wire.h> 
//#include <RtcDS1307.h>

//The clock
//RtcDS1307<TwoWire> Rtc(Wire);

//#define countof(a) (sizeof(a) / sizeof(a[0]))

/*
* @brief get a char * reprsenting the date
*/
char * get_Date();


/*
 * Initializes the clock
 * 
 */

void init_Clock();


/*
 * @brief Setup of the sensor
*//*
void setup () 
{
  
    Serial.begin(9600);
    init_Clock();
}*/


/*
 * @brief Loop where the sensor will be stuck in
*/
/*
void loop () 
{
    char * date = get_Date();
    Serial.print(date);
    Serial.println();

    delete(date);
    delay(10000); // ten seconds
}



char * get_Date()
{
    RtcDateTime now = Rtc.GetDateTime();

    char * date = (char*)malloc(20);
    snprintf(date, 20,
            PSTR("%04u-%02u-%02u %02u:%02u:%02u"),
            now.Year(),
            now.Month(),
            now.Day(),
            now.Hour(),
            now.Minute(),
            now.Second() );

  return date;
}

void init_Clock()
{
    Rtc.Begin();

    //Si l'horloge n'est pas démarée.
    if (!Rtc.GetIsRunning())
    {
        Rtc.SetIsRunning(true);
    }

    //Prends le temps de la compilation
    RtcDateTime compiled = RtcDateTime(__DATE__, __TIME__);

    //Mets la date dans l'horloge
    Rtc.SetDateTime(compiled);
    
    Rtc.SetSquareWavePin(DS1307SquareWaveOut_Low); 

}
*/




#include <ESP32Time.h>
#include <WiFi.h> //Wifi library
#include <WiFiClient.h>
#include <WiFiAP.h>


#include "time.h"
const char* ssid = "partageDeCo";
const char* password = "gabin000";
const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;



//ESP32Time rtc;
ESP32Time rtc(3600);  // offset in seconds GMT+1



void setup() {
    Serial.begin(9600);

    init_Clock();
}

void loop() {
  Serial.println(get_Date());   
  delay(1000);
}


void init_Clock()
{
    // Connect to Wi-Fi
    Serial.print("Connecting to ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected.");
    

    /*---------set with NTP---------------*/
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    struct tm timeinfo;

    if (getLocalTime(&timeinfo))
    {
        rtc.setTimeStruct(timeinfo); 
    }

    //disconnect WiFi as it's no longer needed
    WiFi.disconnect(true);
    WiFi.mode(WIFI_OFF);
}


char * get_Date()
{

    char * date = (char*)malloc(20);
    snprintf(date, 20,
            PSTR("%04u-%02u-%02u %02u:%02u:%02u"),
            rtc.getYear(),
            rtc.getMonth() + 1,
            rtc.getDay(),
            rtc.getHour(true),
            rtc.getMinute(),
            rtc.getSecond()
            );

  return date;
}