import sqlite3, os, hashlib
from flask import Flask, jsonify, render_template, request, g

app = Flask(__name__)
app.database = "sample.db"
app.config["SESSION_PERMANENT"] = False
app.config["SESSION_TYPE"] = "filesystem"
# Session(app)

@app.route('/')
def index():
    return render_template('index.html')

@app.route("/login",methods=['GET','POST'])
def login():
    if request.method == 'POST':
        print(request.form)
        uname,pword = (request.form['username'],request.form['password'])
        g.db = connect_db()
        cur = g.db.execute("SELECT * FROM employees WHERE username = '%s' AND password = '%s'" %(uname, hash_pass(pword)))
        if cur.fetchone():
            g.db.close()        
            return render_template("admin.html")
        g.db.close()                

    return render_template("login.html")

@app.route('/api/v1.0/storeLoginAPI/', methods=['POST'])
def loginAPI():
    if request.method == 'POST':
        uname,pword = (request.json['username'],request.json['password'])
        g.db = connect_db()
        cur = g.db.execute("SELECT * FROM employees WHERE username = '%s' AND password = '%s'" %(uname, hash_pass(pword)))
        if cur.fetchone():
            return render_template("admin.html")
        else:
            result = {'status': 'fail'}
        g.db.close()
        return jsonify(result)


@app.route('/api/v1.0/storeAPI', methods=['GET'])
def storeapi():
    if request.method == 'GET':
        g.db = connect_db()
        curs = g.db.execute("SELECT * FROM shop_items")
        cur2 = g.db.execute("SELECT * FROM employees")
        items = [{'items':[dict(name=row[0], quantity=row[1], price=row[2]) for row in curs.fetchall()]}]
        empls = [{'employees':[dict(username=row[0], password=row[1]) for row in cur2.fetchall()]}]
        g.db.close()
        return jsonify(items+empls)


@app.route('/api/v1.0/storeAPI/<item>', methods=['GET'])
def searchAPI(item):
    g.db = connect_db()
    curs = g.db.execute("SELECT * FROM shop_items WHERE name = '%s'" %item)
    results = [dict(name=row[0], quantity=row[1], price=row[2]) for row in curs.fetchall()]
    print(curs.fetchall());
    print(results)
    g.db.close()
    return jsonify(results)

@app.errorhandler(404)
def page_not_found_error(error):
    return render_template('error_404.html', error=error)

@app.errorhandler(500)
def internal_server_error(error):
    return render_template('error_500.html', error=error)

def connect_db():
    return sqlite3.connect(app.database)

def hash_pass(passw):
	m = hashlib.md5()
	m.update(passw.encode('utf-8'))
	return m.hexdigest()

if __name__ == "__main__":

    if not os.path.exists(app.database):
        with sqlite3.connect(app.database) as connection:
            c = connection.cursor()
            c.execute("""CREATE TABLE shop_items(name TEXT, quantity TEXT, price TEXT,
                        inception TEXT, inception2 TEXT, inception3 TEXT, inception4 TEXT,
                        inception5 TEXT, inception6 TEXT, inception7 TEXT, inception8 TEXT,
                        inception9 TEXT, inception11 TEXT, inception12 TEXT, inception13 TEXT,
                        inception14 TEXT, inception15 TEXT, inception16 TEXT, inception17 TEXT,
                        inception18 TEXT, inception19 TEXT, inception20 TEXT, inception21 TEXT,
                        inception22 TEXT, inception23 TEXT, inception24 TEXT, inception25 TEXT,
                        inception26 TEXT, inception27 TEXT, inception28 TEXT, inception29 TEXT,
                        inception30 TEXT, inception31 TEXT, inception32 TEXT, inception33 TEXT,
                        inception34 TEXT, inception35 TEXT, inception36 TEXT, inception37 TEXT,
                        inception38 TEXT, inception39 TEXT, inception40 TEXT, inception41 TEXT,
                        inception42 TEXT, inception43 TEXT, inception44 TEXT, inception45 TEXT,
                        inception46 TEXT, inception47 TEXT, inception48 TEXT, inception49 TEXT,
                        inception50 TEXT, inception51 TEXT, inception52 TEXT, inception53 TEXT,
                        inception54 TEXT, inception55 TEXT, inception56 TEXT, inception57 TEXT,
                        inception58 TEXT, inception59 TEXT, inception60 TEXT, inception61 TEXT,
                        inception62 TEXT, inception63 TEXT, inception64 TEXT, inception65 TEXT,                        
                        inception66 TEXT, inception67 TEXT, inception68 TEXT, inception69 TEXT)""")
            c.execute("""CREATE TABLE shop_items_old(quantity NUMBER, price TEXT)""")
            c.execute("""CREATE TABLE employees(username TEXT, password TEXT)""")
            c.execute('INSERT INTO shop_items(name, quantity, price, inception, inception2) VALUES("water", "40", "100","a","b")')
            c.execute('INSERT INTO shop_items(name, quantity, price, inception, inception2) VALUES("juice", "40", "110","a","b")')
            c.execute('INSERT INTO shop_items(name, quantity, price, inception, inception2) VALUES("candy", "100", "10","a","b")')


            c.execute("""CREATE TABLE check807d0fbcae7c4b20518d4d85664f6820aafdf936104122c5073e7744c46c4b87(here_man TEXT, secret TEXT)""")
            c.execute("""CREATE TABLE checkf8dad5559eb331eaa32929379eaaf25d2afd5b6eabee7926d1b33c6a7b76fc9d(SSS TEXT, maybe TEXT)""")
            c.execute("""CREATE TABLE check849fb3d286b87c91b78e81ba09c4be829044e8bae2975008887740488b68c8f7(here TEXT, flag TEXT)""")

            c.execute('INSERT INTO check807d0fbcae7c4b20518d4d85664f6820aafdf936104122c5073e7744c46c4b87 VALUES("__TEMPLATE__", "100")')


            c.execute('INSERT INTO shop_items_old VALUES("1", "67")')
            c.execute('INSERT INTO shop_items_old VALUES("3", "70")')
            c.execute('INSERT INTO shop_items_old VALUES("4", "123")')
            c.execute('INSERT INTO shop_items_old VALUES("17", "116")')
            c.execute('INSERT INTO shop_items_old VALUES("19", "125")')
            c.execute('INSERT INTO shop_items_old VALUES("6", "104")')
            c.execute('INSERT INTO shop_items_old VALUES("7", "105")')
            c.execute('INSERT INTO shop_items_old VALUES("8", "53")')
            c.execute('INSERT INTO shop_items_old VALUES("12", "95")')
            c.execute('INSERT INTO shop_items_old VALUES("5", "84")')
            c.execute('INSERT INTO shop_items_old VALUES("13", "53")')
            c.execute('INSERT INTO shop_items_old VALUES("14", "112")')
            c.execute('INSERT INTO shop_items_old VALUES("15", "97")')
            c.execute('INSERT INTO shop_items_old VALUES("2", "84")')
            c.execute('INSERT INTO shop_items_old VALUES("9", "95")')
            c.execute('INSERT INTO shop_items_old VALUES("10", "105")')
            c.execute('INSERT INTO shop_items_old VALUES("11", "53")')
            c.execute('INSERT INTO shop_items_old VALUES("16", "114")')
            c.execute('INSERT INTO shop_items_old VALUES("18", "97")')

            c.execute('INSERT INTO employees VALUES("ram", "{}")'.format(hash_pass("mainhibataunga")))
            c.execute('INSERT INTO employees VALUES("shyam", "{}")'.format(hash_pass("chickennoodles")))
            c.execute('INSERT INTO employees VALUES("ghanshyam", "{}")'.format(hash_pass("selvamSirZindabad")))

            connection.commit()

    app.run() # runs on machine ip address to make it visible on netowrk

