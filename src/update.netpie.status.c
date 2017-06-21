/*
    Things Control. Control anything as you want.
    Copyright (C) 2017  Pornthep Nivatyakul

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    ThingsControl  Copyright (C) 2017  Pornthep Nivatyakul, kaebmoo@gmail.com, seal@ogonan.com
    This program comes with ABSOLUTELY NO WARRANTY;
    This is free software, and you are welcome to redistribute it
    under certain conditions.

	libconfig - A library for processing structured configuration files
	Copyright (C) 2005-2010  Mark A Lindner

	This file is part of libconfig.

 *                                  _   _ ____  _
 *  Project                     ___| | | |  _ \| |
 *                             / __| | | | |_) | |
 *                            | (__| |_| |  _ <| |___
 *                             \___|\___/|_| \_\_____|
 *
 * Copyright (C) 1998 - 2015, Daniel Stenberg, <daniel@haxx.se>, et al.
 *
 * This software is licensed as described in the file COPYING, which
 * you should have received as part of this distribution. The terms
 * are also available at https://curl.haxx.se/docs/copyright.html.
 *
*/

#include <stdio.h>
#include <string.h>
#include <curl/curl.h>
#include <stdlib.h>

#include <fcntl.h>
#ifdef WIN32
#include <io.h>
#else
#include <unistd.h>
#endif
#include <sys/types.h>
#include <sys/stat.h>


#if LIBCURL_VERSION_NUM < 0x070c03
#error "upgrade your libcurl to no less than 7.12.3"
#endif

#include <libconfig.h>

int status_update(char *status)
{

	config_t cfg;


  CURL *curl;
  CURLcode res;

  typedef struct {
		char *key;
		char *secret;
		char *param;
		char *uri;
  } app_key;

  app_key AppKey;

  const char *key = "";
  const char *secret = "";
  const char *param = ""; 	/* auth= */
  const char *uri = ""; 	/* https://api.netpie.io/topic */
  const char *app_id = ""; 	/* /ThingsControl */
  const char *id = "";		/* /seal */
  const char *topic = "";	/* /status?retain& */
  /* https://api.netpie.io/topic/ThingsControl/seal/status?retain&auth=key:secret */
  char *update_status;

  update_status = malloc(strlen(status)) + 1;
  strcpy(update_status, "");
  strcpy(update_status, status);

  config_init(&cfg);
    /* Read the file. If there is an error, report it and exit. */
  if(! config_read_file(&cfg, "thingscontrol.conf"))
  {
    fprintf(stderr, "%s:%d - %s\n", config_error_file(&cfg),
            config_error_line(&cfg), config_error_text(&cfg));
    config_destroy(&cfg);
    return(EXIT_FAILURE);
  }
    /* Get the store name. */
  if(config_lookup_string(&cfg, "key", &key)
		&& config_lookup_string(&cfg, "secret", &secret)
		&& config_lookup_string(&cfg, "param", &param)
		&& config_lookup_string(&cfg, "uri", &uri)
		&& config_lookup_string(&cfg, "app_id", &app_id)
		&& config_lookup_string(&cfg, "id", &id)
		&& config_lookup_string(&cfg, "topic", &topic) ) {
			//printf("key: %s %s %s %s %s %s\n\n", uri, app_id, id, topic, key, secret);
  }
  else
    fprintf(stderr, "No 'key' setting in configuration file.\n");



  int alloc = 0;

  AppKey.key = malloc(strlen(key)+1);
  AppKey.secret = malloc(strlen(secret)+1);
  alloc += strlen(param) + strlen(key) + strlen(":") + strlen(secret);
  alloc++;
  AppKey.param = malloc(alloc);
  AppKey.uri = malloc(strlen(uri)+strlen(app_id)+strlen(id)+strlen(topic)+strlen(param)+strlen(key)+strlen(":")+strlen(secret)+1);
  strcpy(AppKey.uri, "");

  strcpy(AppKey.key, key);
  strcpy(AppKey.secret, secret);
  strcpy(AppKey.param, param);

  /* In windows, this will init the winsock stuff */
  curl_global_init(CURL_GLOBAL_ALL);

  /* get a curl handle */
  curl = curl_easy_init();
  if(curl) {
    /* First set the URL that is about to receive our PUT. This URL can
       just as well be a https:// URL if that is what should receive the
       data. */

    AppKey.param = strcat(strcat(strcat(AppKey.param, AppKey.key),":"), AppKey.secret);
    AppKey.uri = strcat(strcat(strcat(strcat(strcat(AppKey.uri,uri), app_id), id), topic), AppKey.param);
    //printf("Parameter %s \nuri %s\n", AppKey.param, AppKey.uri);

	/*
    curl_easy_setopt(curl, CURLOPT_URL, "https://api.netpie.io/topic/ThingsControl/seal/status?retain&auth=key:secret");
    */
    curl_easy_setopt(curl, CURLOPT_URL, AppKey.uri);

    /* Now specify the PUT data */

    curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, update_status);

    /* Perform the request, res will get the return code */
    res = curl_easy_perform(curl);
    //printf("return code %d\n", res);
		printf("\n");

    /* Check for errors */
    if(res != CURLE_OK)
      fprintf(stderr, "curl_easy_perform() failed: %s\n",
              curl_easy_strerror(res));

    /* always cleanup */
    curl_easy_cleanup(curl);
  }
  curl_global_cleanup();
  free(AppKey.param);
  free(AppKey.key);
  free(AppKey.secret);
  free(AppKey.uri);

  return 0;
}


const int RELAY = 16; /* automation phat BCM 16 GPIO 27 physical 36 */

int main(int argc, char *argv[])
{
	int DELAY;

	printf("argc: %d\n", argc);
	if (argc < 2) {
		printf("Usage: %s 5\nPlease input delay time (1 - 60 second)\n", argv[0]);
		exit(1);
	}

	DELAY = atoi(argv[1]);
	if (DELAY > 60 || DELAY < 1)
		DELAY = 1;

	DELAY = DELAY * 1000;

	printf("Status update every %d sec.\n", DELAY/1000);

	wiringPiSetupGpio(); // Initialize wiringPi -- using Broadcom pin numbers

	for(;;) {
		if (digitalRead(RELAY) == 1)
		{
			printf("Relay Status: ON \n");
			status_update("ON");
		}
		else if (digitalRead(RELAY) == 0) {
			printf("Relay Status: OFF \n");
			status_update("OFF");
		}
		delay(DELAY);
	}
	return 0;
}
