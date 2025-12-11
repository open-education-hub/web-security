from django.shortcuts import render
from .forms import StoredForm
from .models import Stored

import hashlib
# Create your views here.


def page(request):

    form = StoredForm()

    if request.method == "POST": 

        saved = StoredForm(request.POST)      

        objects = Stored.objects.all()
        if len(objects) > 0:
            o = objects[len(objects)-1]
            o.stored = hashlib.sha224(o.stored.encode('utf-8')).hexdigest()
            o.save()

        if saved.is_valid():
            saved.save()

        stored = Stored.objects.order_by('-created_at')
        response = render(request, 'list.xml', {"form":form, "stored":stored})

        response.headers["CONTENT-TYPE"] = 'application/xhtml+xml'
        response.set_cookie("flag", "SSS{miami_hot_number}", httponly=True, secure=True, samesite='Lax')

        return response

    return render(request, 'main.html', {"form":form})