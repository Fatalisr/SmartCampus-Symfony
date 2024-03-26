#ifndef TEMPERATUREHUMIDITE_H
#define TEMPERATUREHUMIDITE_H
/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include <DHT_U.h>
#include "../variables.h"

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

void initTempHumSensor();
void getHumTempvalue(float & hum, float & temp);


/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

void getHumTempTask(void *parameter);

#endif
