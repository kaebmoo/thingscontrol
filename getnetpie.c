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
 * Shows how the write callback function can be used to download data into a
 * chunk of memory instead of storing it in a file.
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
#include <stdlib.h>
#include <string.h>

#include <curl/curl.h>
#include <json-c/json.h>

struct MemoryStruct {
  char *memory;
  size_t size;
};


void json_parse(json_object * jobj) {
 enum json_type type;
 json_object_object_foreach(jobj, key, val) {
 type = json_object_get_type(val);
 switch (type) {
 case json_type_string: 
	printf("type: json_type_string, ");
 	printf("value: %s\n", json_object_get_string(val));
 	break;
 case json_type_int: 
	printf("type: json_type_int, ");
        printf("value: %d\n", json_object_get_int(val));
        break;
 }
 }
 
/*
 enum json_type type;
 json_object_object_foreach(jobj, key, val) {
 	type = json_object_get_type(val);
 	switch (type) {
 		case json_type_int: printf("type: json_type_int, ");
 			printf("value: %dn", json_object_get_int(val));
 			break;
 	}
 }
*/
}


static size_t WriteMemoryCallback(void *contents, size_t size, size_t nmemb, void *userp)
{
  size_t realsize = size * nmemb;
  struct MemoryStruct *mem = (struct MemoryStruct *)userp;

  mem->memory = realloc(mem->memory, mem->size + realsize + 1);
  if(mem->memory == NULL) {
    /* out of memory! */
    printf("not enough memory (realloc returned NULL)\n");
    return 0;
  }

  memcpy(&(mem->memory[mem->size]), contents, realsize);
  mem->size += realsize;
  mem->memory[mem->size] = 0;

  return realsize;
}

int main(void)
{
  CURL *curl_handle;
  CURLcode res;

  struct MemoryStruct chunk;
  int array_len, i;


  chunk.memory = malloc(1);  /* will be grown as needed by the realloc above */
  chunk.size = 0;    /* no data at this point */

  curl_global_init(CURL_GLOBAL_ALL);

  /* init the curl session */
  curl_handle = curl_easy_init();

  /* specify URL to get */
  curl_easy_setopt(curl_handle, CURLOPT_URL, "https://api.netpie.io/topic/ThingsControl/seal/status?auth=SvZc5fyI9gpRaTv:tdyE0XGekaIi1orjaeHjBXGn2");

  /* send all data to this function  */
  curl_easy_setopt(curl_handle, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);

  /* we pass our 'chunk' struct to the callback function */
  curl_easy_setopt(curl_handle, CURLOPT_WRITEDATA, (void *)&chunk);

  /* some servers don't like requests that are made without a user-agent
     field, so we provide one */
  curl_easy_setopt(curl_handle, CURLOPT_USERAGENT, "libcurl-agent/1.0");

  /* get it! */
  res = curl_easy_perform(curl_handle);

  /* check for errors */
  if(res != CURLE_OK) {
    fprintf(stderr, "curl_easy_perform() failed: %s\n",
            curl_easy_strerror(res));
  }
  else {
    /*
     * Now, our chunk.memory points to a memory block that is chunk.size
     * bytes big and contains the remote file.
     *
     * Do something nice with it!
     */
    printf("%s\n", chunk.memory);
    printf("%lu bytes retrieved\n", (long)chunk.size);

	json_object *jobj;
	json_object *array;
	int stringlen = 0;
	enum json_tokener_error jerr;
	enum json_type jtype;
	struct json_tokener *tok;
	tok = json_tokener_new();
	char *type_str;

	do {
		stringlen = strlen(chunk.memory);
		jobj = json_tokener_parse_ex(tok, chunk.memory, stringlen);
	} while ((jerr = json_tokener_get_error(tok)) == json_tokener_continue);

	if (jerr != json_tokener_success)
	{
        fprintf(stderr, "Error: %s\n", json_tokener_error_desc(jerr));
        // Handle errors, as appropriate for your application.
	}
	if (tok->char_offset < stringlen) // XXX shouldn't access internal fields
	{
	fprintf(stderr, "Error: tok->char_offset < stringlen");
        // Handle extra characters after parsed object as desired.
        // e.g. issue an error, parse another object from that point, etc...
	}
	jtype = json_object_get_type(jobj);
	switch(jtype) {
		case json_type_null:
		  type_str = "NULL";
		  break;
		case json_type_boolean:
		  type_str = "BOOLEAN";
		  break;
		case json_type_double:
		  type_str = "DOUBLE";
		  break;
		case json_type_int:
		  type_str = "INT";
		  break;
		case json_type_string:
		  type_str = "STRING";
		  printf("%s\n", json_object_get_string(jobj));
		  break;
		case json_type_object:
		  type_str = "OBJECT";
		  break;
		case json_type_array:
		  type_str = "ARRAY";
		  break;
	}
	printf("Type %s\n", type_str);


	printf("new_obj.to_string()=%s\n", json_object_to_json_string(jobj));

	printf("array length %d\n", json_object_array_length(jobj));
	for (i=0; i < json_object_array_length(jobj); i++) {
		printf("%s\n", json_object_to_json_string(json_object_array_get_idx(jobj, i)));	
	}

	array = json_object_object_get(json_object_array_get_idx(jobj,0), "topic");
	printf("payload : %s\n", json_object_to_json_string(array));
	
	array = json_object_object_get(json_object_array_get_idx(jobj,0), "payload");
	printf("payload : %s\n", json_object_to_json_string(array));
	
	array = json_object_object_get(json_object_array_get_idx(jobj,0), "lastUpdated");
	printf("payload : %d\n", json_object_to_json_int(array));

	array = json_object_object_get(json_object_array_get_idx(jobj,0), "retain");
	printf("payload : %d\n", json_object_to_json_boolean(array));

	
	/*
	jobj = json_object_object_get(jobj, "payload");
	printf("topic : %s\n", json_object_to_json_string(jobj));
	*/

  }

  /* cleanup curl stuff */
  curl_easy_cleanup(curl_handle);

  free(chunk.memory);

  /* we're done with libcurl, so clean it up */
  curl_global_cleanup();

  return 0;
}
