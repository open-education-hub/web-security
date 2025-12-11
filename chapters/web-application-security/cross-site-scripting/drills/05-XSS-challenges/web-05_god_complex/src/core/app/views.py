from django.shortcuts import render
from .forms import StoredForm
from .models import Stored
from django.template import Context, Template
from django.template.loader import render_to_string
from django.http import HttpResponse

import xml.etree.cElementTree as ET
from xml.etree.ElementTree import tostring
from xml.dom import minidom
import random
import string
import hashlib
import datetime
from django.conf import settings

times = 0



def set_cookie(response, key, value, days_expire=7):
    if days_expire is None:
        max_age = 365 * 24 * 60 * 60  # one year
    else:
        max_age = days_expire * 24 * 60 * 60
    expires = datetime.datetime.strftime(
        datetime.datetime.utcnow() + datetime.timedelta(seconds=max_age),
        "%a, %d-%b-%Y %H:%M:%S GMT",
    )
    response.set_cookie(
        key,
        value,
        max_age=max_age,
        expires=expires,
        domain=settings.SESSION_COOKIE_DOMAIN,
        secure=settings.SESSION_COOKIE_SECURE or None,
        httponly=True,
        samesite='Lax',
    )

def rs():

    letters = string.ascii_lowercase
    return ''.join(random.choice(letters) for i in range(random.randrange(12, 24)))


def generate_xml(element=rs(), payload=rs(), attr = rs(), attr_value=rs()): 

    root = ET.Element(rs())
    index = random.choice(range(0, 25))

    for i in range(25):
        if i  == index:      
            doc = ET.SubElement(root, element, {attr: attr_value})
            doc.text = payload
        else:
            doc = ET.SubElement(root, rs(), {rs(): rs()})
            doc.text = rs()
         
    return root


# Create your views here.
def page(request):
    global times
    times += 1
    form = StoredForm()
    objects = Stored.objects.order_by('created_at').all()

    response  = render(request, 'main.html', {"form":form})

    if request.method == "POST": 

        saved = StoredForm(request.POST)      

        if saved.is_valid():
            saved = saved.save()

        element, payload, attr, attr_value = rs(), rs(), rs(), rs()

        if len(objects) == 1:

            attr = objects[len(objects)-1].stored 

        if len(objects) == 2:

            attr_value,attr  = objects[len(objects)-1].stored, objects[len(objects)-2].stored

        if len(objects) == 3:

            payload, attr_value, attr = objects[len(objects)-1].stored, objects[len(objects)-2].stored,\
                objects[len(objects)-3].stored

        if len(objects) > 3:

            element, payload, attr_value, attr = objects[len(objects)-1].stored, objects[len(objects)-2].stored,\
                objects[len(objects)-3].stored,  objects[len(objects)-4].stored

        root = generate_xml(element, payload, attr, attr_value)
        content = 'application/xhtml+xml'

        try:
            xml_str = minidom.parseString(ET.tostring(root)).toprettyxml(indent="\t")
        except Exception as e:
            xml_str = "You are on the good road, keep trying! Hint: make the right order!\
                Btw: every time this happens the database gets deleted! :)"
            objects.delete()
            content = "text/html"

        template = Template(xml_str)
        context = Context({})

        response = HttpResponse(template.render(context))
        set_cookie(response, 'flag', 'SSS{got_some_complex?}')
        response.headers["CONTENT-TYPE"] = content

    print(times)
    if times == 1000:
        times = 0
        objects.delete()


    return response