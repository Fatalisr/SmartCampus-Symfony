#include <Arduino.h>

// CONNECTIONS:
// DS1307 SDA --> SDA
// DS1307 SCL --> SCL
// DS1307 VCC --> 5v
// DS1307 GND --> GND


#include <Wire.h> 
#include <RtcDS1307.h>

//The clock
RtcDS1307<TwoWire> Rtc(Wire);

#define countof(a) (sizeof(a) / sizeof(a[0]))

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
*/
void setup () 
{
  
    Serial.begin(9600);
    init_Clock();
}


/*
 * @brief Loop where the sensor will be stuck in
*/
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

    RtcDateTime compiled = RtcDateTime(__DATE__, __TIME__);


    Rtc.SetDateTime(compiled);
    
    if (!Rtc.GetIsRunning())
    {
        Rtc.SetIsRunning(true);
    }
}
