from django.shortcuts import render
from .forms import *




# Create your views here.


def page(request):

    r11 = Retrogenesis11()
    r21 = Retrogenesis21()
    r22 = Retrogenesis22()
    r31 = Retrogenesis31()
    r32 = Retrogenesis32()
    r33 = Retrogenesis33()
    r41 = Retrogenesis41()
    r42 = Retrogenesis42()
    r43 = Retrogenesis43()
    r44 = Retrogenesis44()

    data = {"r11":r11, "r21":r21, "r22":r22, "r31":r31, "r32":r32, "r33":r33,\
            "r41":r41, "r42":r42, "r43":r43, "r44":r44}

    if request.method == "GET":

        data['r41'] = Retrogenesis41(data=request.GET)
        data['r42'] = Retrogenesis42(data=request.GET)
        data['r43'] = Retrogenesis43(data=request.GET)
        data['r44'] = Retrogenesis44(data=request.GET)

        return render(request, "main.html", data)

    if request.method == "POST":

        # start propagating
        # first level
        data['r41'] = Retrogenesis41(data=request.POST)
        data['r42'] = Retrogenesis42(data=request.POST)
        data['r43'] = Retrogenesis43(data=request.POST)
        data['r44'] = Retrogenesis44(data=request.POST)

        if not all([f.is_valid() for f in [ data['r41'], data['r42'], data['r43'], data['r43']]]):
            return render(request, 'main.html', {"invalid":"invalid parameter for last level"})

        #third level
        data_r31 = {}
        data_r31["field1"] = request.POST['field411']
        data_r31["field2"] = request.POST['field412']
        data_r31["field3"] = request.POST['field413']
        data['r31'] = Retrogenesis31(data_r31)

        data_r32 = {}
        data_r32["field1"] = request.POST['field414']
        data_r32["field2"] = request.POST['field422'] + request.POST['field431'] + request.POST['field443']
        data_r32["field3"] = request.POST['field421']
        data['r32'] = Retrogenesis32(data_r32)

        data_r33 = {}
        data_r33["field1"] = request.POST['field424']
        data_r33["field2"] = request.POST['field423']
        data_r33["field3"] = request.POST['field433']
        data['r33'] = Retrogenesis33(data_r33)

        #second level
        data_r21 = {}
        data_r21["field1"] = data['r31'].data['field1'] + data['r33'].data['field3']
        data_r21["field2"] = data['r33'].data['field2'] + data['r32'].data['field2']
        data['r21'] = Retrogenesis21(data_r21)

        data_r22 = {}
        data_r22["field1"] = data['r32'].data['field1'] 
        data_r22["field2"] = data['r33'].data['field1'] 
        data['r22'] = Retrogenesis22(data_r22)

        # first level
        data_r11 = {}
        data_r11["field1"] = data['r21'].data['field1'] + data['r22'].data['field2'] + data['r22'].data['field1'] + data['r21'].data['field2']
        data['r11'] = Retrogenesis11(data_r11)
        data['result'] = data['r11'].data['field1'] # "<script>alert(document.cookie)</script>"
        res = render(request, 'main.html', data)

        res.set_cookie("flag", "SSS{retr0_music_for_the_masses}", httponly=True, secure=True, samesite='Lax')

        return res