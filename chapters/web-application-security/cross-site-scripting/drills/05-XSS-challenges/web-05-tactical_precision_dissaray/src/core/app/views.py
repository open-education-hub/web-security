from django.shortcuts import render
from .forms import Dissaray


# Create your views here.


def page(request):


    if request.method == "POST":

        data = Dissaray()
        created = dict(request.POST)
        created.pop("csrfmiddlewaretoken", None)
        created = "<br>".join([x[0] for x in created.values()])

        context = {"data":data, "created": created}

        return render(request, "main.html", context=context)
    
    data = Dissaray(data=request.GET)

    res = render(request, "main.html", {"data":data})

    res.set_cookie("flag", "SSS{tactical_precision_dissaray_for_beginners}", httponly=True, secure=True, samesite='Lax')

    return res