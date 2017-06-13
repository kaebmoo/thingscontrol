/***************************************************************************
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
 * You may opt to use, copy, modify, merge, publish, distribute and/or sell
 * copies of the Software, and permit persons to whom the Software is
 * furnished to do so, under the terms of the COPYING file.
 *
 * This software is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY
 * KIND, either express or implied.
 *
 ***************************************************************************/
/* <DESC>
 * simple HTTP PUT using the easy interface
 * </DESC>
 */

/*
    Things Control. Control anything you want.
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


int main(void)
{
  CURL *curl;
  CURLcode res;
  typedef struct {
	char *key;
	char *secret;
	char *param;
	char *uri;
  } app_key;

  app_key AppKey;

  char *key = "SvZc5fyI9gpRaTv";
  char *secret = "tdyE0XGekaIi1orjaeHjBXGn2";
  char *param = "auth=";
  char *netpie = "https://api.netpie.io/topic";
  char *app_id = "/ThingsControl";
  char *id = "/seal";
  char *topic = "/status?retain&";
  /* https://api.netpie.io/topic/ThingsControl/seal/status?retain&auth=SvZc5fyI9gpRaTv:tdyE0XGekaIi1orjaeHjBXGn2 */

  int alloc = 0;

  AppKey.key = malloc(strlen(key)+1);
  AppKey.secret = malloc(strlen(secret)+1);
  alloc += strlen(param) + strlen(key) + strlen(":") + strlen(secret);
  alloc++;
  AppKey.param = malloc(alloc);
  AppKey.uri = malloc(strlen(netpie)+strlen(app_id)+strlen(id)+strlen(topic)+strlen(param)+strlen(key)+strlen(":")+strlen(secret)+1);

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
    /*
    curl_easy_setopt(curl, CURLOPT_URL, "https://api.netpie.io/topic/ThingsControl/seal/status?retain&auth=SvZc5fyI9gpRaTv:tdyE0XGekaIi1orjaeHjBXGn2");
    */
    /*  curl_easy_setopt(curl, CURLOPT_HTTPHEADER, "Content-Type: application/x-www-form-urlencoded"); */
    /* printf("Parameter %s\n", AppKey.param); */
    AppKey.param = strcat(strcat(strcat(AppKey.param, AppKey.key),":"), AppKey.secret);
    AppKey.uri = strcat(strcat(strcat(strcat(strcat(AppKey.uri,netpie), app_id), id), topic), AppKey.param);
    printf("Parameter %s, \nuri %s\n", AppKey.param, AppKey.uri);

    curl_easy_setopt(curl, CURLOPT_URL, AppKey.uri);

    /* Now specify the PUT data */
    /* curl_easy_setopt(curl, CURLOPT_POSTFIELDS, AppKey.param); */
    /* curl_easy_setopt(curl, CURLOPT_UPLOAD, 1L);  */
    curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, "ON");

    /* Perform the request, res will get the return code */
    res = curl_easy_perform(curl);
    printf("return code %d\n", res);
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

