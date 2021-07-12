from traceback import print_tb
import PySimpleGUI as sg
from PySimpleGUI.PySimpleGUI import theme
import random
import requests
import json
import bcrypt
import webbrowser

font = ("Arial, 25")
font2 = ("Arial, 20")
temas = ['Black', 'BlueMono', 'BluePurple', 'BrightColors', 'BrownBlue', 'Dark', 'Dark2', 'DarkAmber', 'DarkBlack', 'DarkBlack1', 'DarkBlue', 'DarkBlue1', 'DarkBlue10', 'DarkBlue11', 'DarkBlue12', 'DarkBlue13', 'DarkBlue14', 'DarkBlue15', 'DarkBlue16', 'DarkBlue17', 'DarkBlue2', 'DarkBlue3', 'DarkBlue4', 'DarkBlue5', 'DarkBlue6', 'DarkBlue7', 'DarkBlue8', 'DarkBlue9', 'DarkBrown', 'DarkBrown1', 'DarkBrown2', 'DarkBrown3', 'DarkBrown4', 'DarkBrown5', 'DarkBrown6', 'DarkGreen', 'DarkGreen1', 'DarkGreen2', 'DarkGreen3', 'DarkGreen4', 'DarkGreen5', 'DarkGreen6', 'DarkGrey', 'DarkGrey1', 'DarkGrey2', 'DarkGrey3', 'DarkGrey4', 'DarkGrey5', 'DarkGrey6', 'DarkGrey7', 'DarkPurple', 'DarkPurple1', 'DarkPurple2', 'DarkPurple3', 'DarkPurple4', 'DarkPurple5', 'DarkPurple6', 'DarkRed', 'DarkRed1', 'DarkRed2', 'DarkTanBlue', 'DarkTeal', 'DarkTeal1', 'DarkTeal10', 'DarkTeal11', 'DarkTeal12', 'DarkTeal2', 'DarkTeal3', 'DarkTeal4', 'DarkTeal5', 'DarkTeal6', 'DarkTeal7', 'DarkTeal8', 'DarkTeal9', 'Default', 'Default1', 'DefaultNoMoreNagging', 'Green', 'GreenMono', 'GreenTan', 'HotDogStand', 'Kayak', 'LightBlue', 'LightBlue1', 'LightBlue2', 'LightBlue3', 'LightBlue4', 'LightBlue5', 'LightBlue6', 'LightBlue7', 'LightBrown', 'LightBrown1', 'LightBrown10', 'LightBrown11', 'LightBrown12', 'LightBrown13', 'LightBrown2', 'LightBrown3', 'LightBrown4', 'LightBrown5', 'LightBrown6', 'LightBrown7', 'LightBrown8', 'LightBrown9', 'LightGray1', 'LightGreen', 'LightGreen1', 'LightGreen10', 'LightGreen2', 'LightGreen3', 'LightGreen4', 'LightGreen5', 'LightGreen6', 'LightGreen7', 'LightGreen8', 'LightGreen9', 'LightGrey', 'LightGrey1', 'LightGrey2', 'LightGrey3', 'LightGrey4', 'LightGrey5', 'LightGrey6', 'LightPurple', 'LightTeal', 'LightYellow', 'Material1', 'Material2', 'NeutralBlue', 'Purple', 'Reddit', 'Reds', 'SandyBeach', 'SystemDefault', 'SystemDefault1', 'SystemDefaultForReal', 'Tan', 'TanBlue', 'TealMono', 'Topanga']
sg.theme(random.choice(temas))

def make_ventana_inicio_sesion():
    layout = [[sg.Text("Accede a tu Cuenta.", font=font)], [sg.Text("Email")], [sg.Input(key='email', size=(47, 1))], [sg.Text("Contraseña")], [sg.Input(key='pass', size=(47, 1), password_char='*')], [sg.Button("Iniciar Sesion")], [sg.Text("¿No tienes cuenta? Registrate!")], [sg.Button('Registrarme')],]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))


def make_ventana_inicio_sesion_error():
    layout = [[sg.Text("No se encontro el mail ingresado en la base de datos, o la contraseña es incorrecta.", font=font2)], [sg.Text("Intenta acceder nuevamente.", font=font2)], [sg.Text("Email")], [sg.Input(key='email', size=(47, 1))], [sg.Text("Contraseña")], [sg.Input(key='pass', size=(47, 1), password_char='*')], [sg.Button("Iniciar Sesion")], [sg.Text("¿No tienes cuenta? Registrate!")], [sg.Button('Registrarme')]]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))

def make_ventana_ok():
    layout = [[sg.Text("OK", font=font)]]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))

window = make_ventana_inicio_sesion()
event, values = window.read()

if event == "Iniciar Sesion":
    email = values['email']
    password = values['pass']
    url_user_check = f"http://localhost/server-central_JEE/ws/?accion=verUsuario&email={email}"
    response = requests.get(url_user_check)
    if(response.status_code == 200):
        json_data = json.loads(response.text)
        hashed_pass = json_data['result']['contrasenia']
        password_bytes = bytes(password, encoding='utf-8')
        hashed_pass_bytes = bytes(hashed_pass, encoding='utf-8')
        if(bcrypt.checkpw(password_bytes, hashed_pass_bytes) == True):
            window = make_ventana_ok()
            event, values = window.read()
        else:
            window.close()
            window = make_ventana_inicio_sesion_error()
            event, values = window.read()
            if(event == "Iniciar Sesion"):
                email = values['email']
                password = values['pass']
                url_user_check = f"http://localhost/server-central_JEE/ws/?accion=verUsuario&email={email}"
                response = requests.get(url_user_check)
                if(response.status_code == 200):
                    json_data = json.loads(response.text)
                    hashed_pass = json_data['result']['contrasenia']
                    password_bytes = bytes(password, encoding='utf-8')
                    hashed_pass_bytes = bytes(hashed_pass, encoding='utf-8')
                    if(bcrypt.checkpw(password_bytes, hashed_pass_bytes) == True):
                        window = make_ventana_ok()
                        event, values = window.read()
    else:
        window.close()
        window = make_ventana_inicio_sesion_error()
        event, values = window.read()
        if(event == "Iniciar Sesion"):
            email = values['email']
            password = values['pass']
            url_user_check = f"http://localhost/server-central_JEE/ws/?accion=verUsuario&email={email}"
            response = requests.get(url_user_check)
            if(response.status_code == 200):
                json_data = json.loads(response.text)
                hashed_pass = json_data['result']['contrasenia']
                password_bytes = bytes(password, encoding='utf-8')
                hashed_pass_bytes = bytes(hashed_pass, encoding='utf-8')
                if(bcrypt.checkpw(password_bytes, hashed_pass_bytes) == True):
                    window = make_ventana_ok()
                    event, values = window.read()
elif event == "Registrarme":
    webbrowser.open(r'http://127.0.0.1:8000/registro')
elif event == sg.WIN_CLOSED:
    exit()