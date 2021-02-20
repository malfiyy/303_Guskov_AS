#!/usr/local/bin/python
# coding: utf-8

import os
path = os.getcwd()

# #                                                               genres.txt
# genres_sql = "CREATE TABLE IF NOT EXIST genres (\
# genre varchar(255)\
# );\n"
# with open( path + '/genres.txt', 'r' ) as fp:
#     lines = fp.read().splitlines()

# genres_sql += "INSERT INTO genres (genre) VALUES "
# for l in lines:
#     genres_sql += "(\"" +l+ "\"),"

# genres_sql = genres_sql[:-1] + '; \n'
# #print( genres_sql )

# #  `


#                                                               movies.csv
movies_sql = "CREATE TABLE IF NOT EXISTS movies (\
id INTEGER PRIMARY KEY, \
title varchar(255), \
year varchar(255), \
genres varchar(255)\
);\n"
with open( path + '/movies.csv', 'r' ) as fp:
    lines = fp.read().splitlines()

counter = 0
movies_sql += "INSERT INTO movies (id, title, year, genres) VALUES \n"
for l in lines:
    if counter == 0:
        counter = 1
    else:
        sp = l.replace('\"\"', "\'").replace("\"", "").split(',')
        check = "false"
        if len(sp)>3 :
            sp = l.split('"')
            sp[0] = sp[0][:-1]
            sp[2] = sp[2].replace(',', '')
        sp[1] = sp[1].replace("\'", "\\'")
        yearMas = sp[1].split('(')
        year = yearMas[ len(yearMas)-1 ].split(")")[0]
        sp[1] = sp[1].replace(" ("+year+")", "").replace("\"", "")
        movies_sql += "(" + sp[0] + ',"'+sp[1] + "\",\""+year+ "\",\"" + sp[2] + "\"),\n"

movies_sql = movies_sql[:-2] + ';\n'



# #                                                               occupation.txt
# occupation_sql = "CREATE TABLE IF NOT EXIST occupation (\
# occupation varchar(255)\
# );\n"
# with open( path + '/occupation.txt', 'r' ) as fp:
#     lines = fp.read().splitlines()

# occupation_sql += "INSERT INTO occupation (occupation) VALUES "

# for l in lines:
#     occupation_sql += "(\"" +l+ "\"),"

# occupation_sql = occupation_sql[:-1] + '; \n'

#                                                               raiting.sql
ratings_sql = "CREATE TABLE IF NOT EXISTS ratings (\
id INTEGER PRIMARY KEY, \
user_id INT, \
movie_id INT, \
raiting FLOAT, \
timestamp TIMESTAMP\
);\n"
with open( path + '/ratings.csv', 'r' ) as fp:
    lines = fp.read().splitlines()

ratings_sql += "INSERT INTO ratings (user_id, movie_id, raiting, timestamp) VALUES "
counter = 0
for l in lines:
    if counter == 0:
        counter = 1
    else:
        sp = l.split(',')
        ratings_sql += "(" + sp[0] + ','+sp[1] + "," + sp[2] + "," + sp[3]+ "),\n"

ratings_sql = ratings_sql[:-2] + '; \n'

#                                                               tags.sql
tags_sql = "CREATE TABLE IF NOT EXISTS tags (\
id INTEGER PRIMARY KEY, \
user_id INT, \
movie_id INT, \
tag varchar(255), \
timestamp TIMESTAMP\
);\n"
with open( path + '/tags.csv', 'r' ) as fp:
    lines = fp.read().splitlines()

tags_sql += "INSERT INTO tags (user_id, movie_id, tag, timestamp) VALUES "
counter = 0
for l in lines:
    if counter == 0:
        counter = 1
    else:
        sp = l.replace("\"", "").split(',')
        sp[2] = sp[2].replace("\'", "\\'");
        tags_sql += "(" + sp[0] + ','+sp[1] + ",\"" + sp[2] + "\"," + sp[3]+ "),\n"

tags_sql = tags_sql[:-2] + '; \n'


#                                                               users.sql
users_sql = "CREATE TABLE IF NOT EXISTS users (\
id INTEGER PRIMARY KEY, \
name varchar(255), \
email varchar(255), \
gender varchar(255), \
register_date DATE, \
occupation varchar(255)\
);\n"
with open( path + '/users.txt', 'r' ) as fp:
    lines = fp.read().splitlines()

users_sql += "INSERT INTO users (id, name, email, gender, register_date, occupation) VALUES "
counter = 0
for l in lines:
    sp = l.split('|')
    users_sql += "(" + sp[0] + ',\"'+sp[1] + "\",\"" + sp[2] + "\",\"" + sp[3]+ "\",\"" + sp[4] + "\",\"" + sp[5] + "\"),\n"

users_sql = users_sql[:-2] + '; \n'


file = open("db_init.sql","w")
file.write("DROP TABLE IF EXISTS movies;\n")
file.write("DROP TABLE IF EXISTS ratings;\n")
file.write("DROP TABLE IF EXISTS tags;\n")
file.write("DROP TABLE IF EXISTS users;\n")
file.write(movies_sql)
file.write(ratings_sql)
file.write(tags_sql)
file.write(users_sql)