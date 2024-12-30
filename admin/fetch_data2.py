import pymysql

# MySQL 데이터베이스 연결
connection = pymysql.connect(
    host="localhost",         # MySQL 서버 주소
    user="quantumcode",     # 사용자 이름
    password="12345", # 비밀번호
    database="quantumcode"  # 데이터베이스 이름
)

# 커서 생성
cursor = connection.cursor()

# SQL 쿼리 실행
query = "SELECT * FROM teachers"
cursor.execute(query)

# 결과 가져오기
rows = cursor.fetchall()

# 결과 출력
for row in rows:
    print(row)

# 연결 닫기
cursor.close()
connection.close()