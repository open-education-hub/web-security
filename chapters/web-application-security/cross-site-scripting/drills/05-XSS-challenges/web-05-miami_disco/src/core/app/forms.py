from django.forms import ModelForm
from .models import Stored

class StoredForm(ModelForm):
    class Meta:
        model = Stored
        fields = ['stored']