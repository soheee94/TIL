# 02. 도커 엔진
## 2.4 Dockerfile
### 2.4.1 이미지를 생성하는 방법
**지금까지 해보았던 이미지 생성 방법**
```
1. 아무것도 존재하지 않는 이미지로 컨테이너를 생성
2. 애플리케이션을 위한 환경을 설치하고 소스코드 등을 복사해 잘 동작하는 것을 확인
3. 컨테이너를 이미지로 커밋
```
- 단점: 환경 구성을 위해 일일이 수작으로 패키지를 설치하고 소스코드를 깃이나 호스트에서 복제
- 장점: 애플리케이션을 구동하고 이미지로 커밋하기 때문에 이미지의 동작을 보장

**DockerFile**
- 도커는 위와 같은 과정을 손쉽게 기록하고 수행할 수 있는 빌드 명령어를 제공
- 이러한 작업을 기록한 파일의 이름을 <u>DockerFile</u> 이라함
- 장점
    - 직접 컨테이너를 생성하고 이미지로 커밋해야한느 번거러움을 덜 수 있다.
    - 빌드 및 배포를 자동화
    - 애플리케이션에 필요한 패키지 설치 등을 명확히 할 수 있음
    
### 2.4.2 DockerFile 작성
웹 서버 이미지를 생성하는 도커 파일 예제
```
mkdir dockerfile && cd dockerfile                                   
echo test >> test.html

vi Dockerfile
```
도커 엔진은 Dockerfile을 읽어 들일 때 기본적으로 현재 디렉터리에 있는 Dockerfile 이라는 이름을 가진 파일을 선택한다!
```
FROM ubuntu:14.04
MAINTAINER sohee
LABEL "purpose"="practice"
RUN apt-get update
RUN apt-get install apache2 -y
ADD test.html /var/www/html
WORkDIR /var/www/html
RUN ["/bin/bash", "-c", "echo hello >> test2.html"]
EXPOSE 80
CMD apachetcl -DFOREGROUND                         
```

- `FROM` : 생성할 이미지의 베이스가 될 이미지, 반드시 한 번 이상 입력해야함
- `MAINTAINER` : 이미지를 생성한 개발자의 정보 (도커 1.13.0 버전 이후 사용안함 -> LABEL 로 교체해 표현)
- `LABEL` : 메타데이터 추가 / `키:값` 의 형태로 저장
- `RUN` : 이미지를 만들기 위해 컨테이너 내부에서 명령어 실행, 이미지를 빌드할 때 별도의 입력을 받아야 하는 RUN이 있다면 이를 오류로 간주하고 종료함

    배열의 형태로도 사용이 가능하다 -> 
    `RUN ['실행 가능한 파일', '명령줄 인자 1', '명령줄 인자 2', ...]`
- `ADD` : 파일을 이미지에 추가 
- `WORKDIR` : 명령어를 실행할 디렉터리
- `EXPOSE` : 이미지에서 노출할 포트
- `CMD` : 컨테이너가 시작될 때마다 실행할 명령어 설정