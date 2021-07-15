import requests
from geopy.geocoders import Nominatim

CIUDAD = "Maldonado"

geolocator = Nominatim(user_agent="rsm")
location = geolocator.geocode(CIUDAD+','+ 'Uruguay')
lat = location.latitude
lon = location.longitude

API_KEY = "92ecbd09b28050e849ee353088902383"
BASE_URL = "https://api.openweathermap.org/data/2.5/weather?"
URL_EXTRA = f"https://api.openweathermap.org/data/2.5/onecall?lat={lat}&lon={lon}&exclude=minutely,daily,alerts&appid={API_KEY}"

URL = BASE_URL + "q=Maldonado" + "&appid=" + API_KEY
response = requests.get(URL_EXTRA)
datos_lluvia = response.json()
lluvia = datos_lluvia["hourly"]
i=0
while i < len(lluvia):
    try:
        precipitacion = lluvia[i]["rain"]["1h"]
        i = 999999
    except KeyError:
        precipitacion = 0
    i += 1
print(precipitacion)

