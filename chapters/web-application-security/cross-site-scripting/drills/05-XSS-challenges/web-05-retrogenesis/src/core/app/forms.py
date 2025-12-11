from django import forms

class Retrogenesis11(forms.Form):

    field1 = forms.CharField(max_length=5, required=False)

class Retrogenesis21(forms.Form):

    field1 = forms.CharField(max_length=5, required=False)
    field2 = forms.CharField(max_length=5, required=False)

class Retrogenesis22(forms.Form):

    field1 = forms.CharField(max_length=5, required=False)
    field2 = forms.CharField(max_length=5, required=False)

class Retrogenesis31(forms.Form):

    field2 = forms.CharField(max_length=5, required=False)
    field1 = forms.CharField(max_length=5, required=False)
    field3 = forms.CharField(max_length=5, required=False)

class Retrogenesis32(forms.Form):

    field3 = forms.CharField(max_length=5, required=False)
    field2 = forms.CharField(max_length=5, required=False)
    field1 = forms.CharField(max_length=5, required=False)

class Retrogenesis33(forms.Form):

    field2 = forms.CharField(max_length=5, required=False)
    field1 = forms.CharField(max_length=5, required=False)
    field3 = forms.CharField(max_length=5, required=False)



class Retrogenesis41(forms.Form):

    field411 = forms.CharField(max_length=5, required=False)
    field412 = forms.CharField(max_length=5, required=False)
    field413 = forms.CharField(max_length=5, required=False)
    field414 = forms.CharField(max_length=5, required=False)


class Retrogenesis42(forms.Form):

    field422 = forms.CharField(max_length=5, required=False)
    field421 = forms.CharField(max_length=5, required=False)
    field424 = forms.CharField(max_length=5, required=False)
    field423 = forms.CharField(max_length=5, required=False)

class Retrogenesis43(forms.Form):

    field433 = forms.CharField(max_length=5, required=False)
    field431 = forms.CharField(max_length=5, required=False)
    field434 = forms.CharField(max_length=5, required=False)
    field432 = forms.CharField(max_length=5, required=False)

class Retrogenesis44(forms.Form):

    field444 = forms.CharField(max_length=5, required=False)
    field443 = forms.CharField(max_length=5, required=False)
    field442 = forms.CharField(max_length=5, required=False)
    field441 = forms.CharField(max_length=5, required=False)
