from django.db import models
from django.db.models.fields import CharField, DateTimeField
from datetime import datetime

class Stored(models.Model):

  stored = CharField(max_length=100)
  created_at = DateTimeField(auto_now_add=True, blank=True)