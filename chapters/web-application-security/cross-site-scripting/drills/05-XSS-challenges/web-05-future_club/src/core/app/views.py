from django.shortcuts import render
from .forms import StoredForm
from django.template import Context, Template
from django.template.loader import render_to_string
from django.http import HttpResponse

import random


# Create your views here.
def page(request):

    form = StoredForm()

    q = request.GET.get('q', random.randint(10,100))

    context = {}

    context['form'] = form
    context['times'] = [t for t in range(int(q))]

    res = render(request, 'main.html', context)

    res.set_cookie("flag", "SSS{future_club_obfuscation}", httponly=True, secure=True, samesite='Lax')

    return res