/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include "date.h"

/*-----------------------------------------------------------------*/
/*                           Variables                             */
/*-----------------------------------------------------------------*/

ESP32Time rtc(3600);  // GMT+1
const long gmtOffset_sec = 0;
const int daylightOffset_sec = 3600;
// Lien vers le serveur ntp
const char* ntpServer = "pool.ntp.org";

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

// Initialisation de l'horloge
void initClock()
{
    /*---------set with NTP---------------*/
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    struct tm timeinfo;
    if (getLocalTime(&timeinfo))
    {
        rtc.setTimeStruct(timeinfo); 
    }
}

// Retourne la date courante
char * getDate()
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