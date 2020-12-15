from flask.ext.wtf import Form, TextField, TextAreaField, SubmitField

class ContactForm(Form):
    name = TextField("Name")
    email = TextField("Email")
    subject = TextField("Subject")
    message = TextField("Message")
    submit = SubmitField("Submit")