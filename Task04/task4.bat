#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo "1. Найти все комедии, выпущенные после 2000 года, которые понравились мужчинам (оценка не ниже 4.5). Для каждого фильма в этом списке вывести название, год выпуска и количество таких оценок."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "SELECT movies.title AS 'Movie', movies.year AS 'Year', COUNT(*) AS 'Комедии, выпущенные после 2000 года, которые понравились мужчинам (оценка не ниже 4.5)' FROM movies INNER JOIN(SELECT * FROM ratings WHERE rating >= 4.5) ratings ON ratings.movie_id = movies.id INNER JOIN(SELECT * FROM users WHERE users.gender = 'male') users ON ratings.user_id = users.id WHERE movies.year > 2000 AND INSTR(genres, 'Comedy')>0 GROUP BY title;"
echo " "

echo "2. Провести анализ занятий (профессий) пользователей - вывести количество пользователей для каждого рода занятий. Найти самую распространенную и самую редкую профессию посетитетей сайта."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS SELECT occupation as 'Occupation', COUNT(occupation) AS 'Number' FROM users GROUP BY occupation;" 
sqlite3 movies_rating.db -box -echo "SELECT * from v; SELECT Occupation, Number FROM(SELECT *, MAX(Number)OVER() AS 'MostPopular', MIN(Number)OVER() AS 'MostUnpopular' FROM v) WHERE Number=MostPopular OR Number=MostUnpopular; DROP VIEW v;"
echo " "

echo "3. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они ценили."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "SELECT DISTINCT title AS 'Movie', u1.name as 'User1', u2.name as 'User2' FROM ratings a, ratings b INNER JOIN movies ON a.movie_id = movies.id INNER JOIN users u1 ON a.user_id = u1.id INNER JOIN users u2 ON b.user_id = u2.id WHERE a.movie_id = b.movie_id AND a.user_id < b.user_id ORDER BY title LIMIT 100;"
echo " "

echo "4. Найти 10 самых свежих оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS SELECT ratings.user_id AS 'userID', ratings.movie_id AS 'movieID', ratings.rating AS 'Rating', MAX(ratings.timestamp) AS 'Date' FROM ratings GROUP BY ratings.user_id ORDER BY timestamp DESC;"
sqlite3 movies_rating.db -box -echo "SELECT movies.title AS 'Movie', users.name AS 'Name', Rating, DATE(Date,'unixepoch') AS 'Date' FROM movies, users, v WHERE movies.id = movieID AND users.id = userID LIMIT 10; DROP VIEW v;"
echo " "

echo "5. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке 'Рекомендуем' для фильмов должно быть написано 'Да' или 'Нет'."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS SELECT movies.title AS 'Movie', movies.year AS 'Year', Rating FROM movies INNER JOIN(SELECT ratings.movie_id, AVG(ratings.rating) AS 'Rating' FROM ratings GROUP BY ratings.movie_id) ratings ON ratings.movie_id = movies.id;"
sqlite3 movies_rating.db -box -echo "SELECT Movie, Year, Rating, CASE WHEN MaxRating = Rating THEN 'Да' ELSE 'Нет' END AS Recommend FROM(SELECT *, MAX(Rating)over() AS 'MaxRating', MIN(Rating)over() AS 'MinRating' FROM v) WHERE Rating = MaxRating OR Rating = MinRating ORDER BY Year, Movie; DROP VIEW v;"
echo " "

echo "6. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-женщины в период с 2010 по 2012 год."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS SELECT ratings.rating AS 'Rating', ratings.timestamp AS 'Date' FROM ratings INNER JOIN(select users.id, users.gender FROM users WHERE users.gender = 'female') users ON ratings.user_id = users.id WHERE DATE(ratings.timestamp,'unixepoch') BETWEEN '2010' and '2012' ORDER BY ratings.timestamp;"
sqlite3 movies_rating.db -box -echo "SELECT COUNT(Rating) AS 'Number of ratings given by women', AVG(Rating) AS 'Average rating' FROM v; drop view v;"
echo " "

echo "7. Составить список фильмов с указанием их средней оценки и места в рейтинге по средней оценке. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS SELECT movies.title AS 'Movie', movies.year AS 'Year', Rating FROM movies INNER JOIN(SELECT ratings.movie_id, AVG(ratings.rating) AS 'Rating' FROM ratings GROUP BY ratings.movie_id) ratings ON ratings.movie_id = movies.id;"
sqlite3 movies_rating.db -box -echo "SELECT *, DENSE_RANK()OVER(ORDER BY Rating DESC) AS 'Place' FROM v ORDER BY Year, Movie LIMIT 20; drop view v;"
echo " "

echo "8. Определить самый распространенный жанр фильма и количество фильмов в этом жанре."
echo "--------------------------------------------------"
sqlite3 movies_rating.db -box -echo "CREATE VIEW v AS WITH tb(id,gen,rest) AS (SELECT id, null, genres FROM movies UNION ALL SELECT id, CASE WHEN INSTR(rest,'|') = 0 THEN rest ELSE substr(rest,1,instr(rest,'|')-1) END, CASE WHEN instr(rest,'|')=0 THEN null ELSE substr(rest,instr(rest,'|')+1) END FROM tb WHERE rest IS NOT null ORDER BY id) SELECT gen AS 'Genres', count(id) AS 'Number' FROM tb WHERE gen IS NOT null GROUP BY gen;"
sqlite3 movies_rating.db -box -echo "SELECT Genres AS 'Cамый распространенный жанр фильма', MAX(Number) AS 'Number of films' FROM v; drop view v;"