from django.forms import Form
from django import forms


class StoredForm(Form):
    
    field = forms.CharField()
    