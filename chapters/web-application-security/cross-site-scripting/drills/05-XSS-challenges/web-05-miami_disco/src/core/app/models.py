from django.db import models
from django.db.models.fields import CharField, DateTimeField

from datetime import datetime
# Create your models here.

class Stored(models.Model):

  stored = CharField(max_length=100)
  created_at = DateTimeField(default=datetime.now, blank=True)