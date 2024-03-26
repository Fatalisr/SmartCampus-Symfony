#ifndef CO2_H
#define CO2_H
/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include "sgp30.h"
#include "../variables.h"

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

void initCO2Sensor();

/*
 * @brief Read the value from the sensor
 * @param ppm The var where the C02 values will be stored
 * @return the error. STATUS_OK if success, STATUS_FAIL in the other case
*/
s16 getCO2Value(u16 &ppm);

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

void getCO2Task(void *parameter);

#endif