
import requests
from pprint import pprint




def sendImg():

    url = "https://fotobudkaraspberry.000webhostapp.com/uploadPhoto.php"
    files = {'photo': open("\Users\Amor\Desktop\download.jpg",'rb')}
    pprint(file)
    payload = {'code': 'test333', 'catalog_id': 1 }

    response = requests.request("POST", url, data=payload, files=files)

    print(response.text)

sendImg()
