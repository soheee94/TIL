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

### 2.4.3 Dockerfile 빌드
#### 2.4.3.1 이미지 생성

1. 도커 파일 빌드
    ```
    docker build -t mybuild:0.0 ./              
    [+] Building 0.1s (11/11) FINISHED                                                                                         
     => [internal] load build definition from Dockerfile                                                                  0.0s
     => => transferring dockerfile: 490B                                                                                  0.0s
     => [internal] load .dockerignore                                                                                     0.0s
     => => transferring context: 2B                                                                                       0.0s
     => [internal] load metadata for docker.io/library/ubuntu:14.04                                                       0.0s
     => [internal] load build context                                                                                     0.0s
     => => transferring context: 30B                                                                                      0.0s
     => [1/6] FROM docker.io/library/ubuntu:14.04                                                                         0.0s
     => CACHED [2/6] RUN apt-get update                                                                                   0.0s
     => CACHED [3/6] RUN apt-get install apache2 -y                                                                       0.0s
     => CACHED [4/6] ADD test.html /var/www/html                                                                          0.0s
     => CACHED [5/6] WORKDIR /var/www/html                                                                                0.0s
     => CACHED [6/6] RUN ["/bin/bash","-c","echo hello >> test2.html"]                                                    0.0s
     => exporting to image                                                                                                0.0s
     => => exporting layers                                                                                               0.0s
     => => writing image sha256:0ec3a60442c72f43b56e7b5dbac54cfdefda9049b03f4177d30db7d9028db58c                          0.0s
     => => naming to docker.io/library/mybuild:0.0                                                                        0.0s
    ```
    ```
    docker build -t [이미지 이름] [Dockerfile이 저장된 경로]
    ```
    - `-t` : 이미지의 이름 설정, 설정하지 않으면 16진수 형태의 이름으로 저장됨
    
2. 컨테이너 실행
    ```
   docker run -d -P --name myserver mybuild:0.0
   ```
   - `-P` : EXPOSE로 노출된 포트를 호스트에서 사용 가능한 포트에 연결

3. 연결된 포트 확인
    ```
   docker port myserver                        
   80/tcp -> 0.0.0.0:55007
   80/tcp -> :::55007
   ```
   -> `127.0.0.1:55007/test2.html` 실행 확인!
   
#### 2.4.3.2 빌드 과정 살펴보기
##### 빌드 컨텍스트
- 이미지를 생성하는데 필요한 각종 파일, 소스코드, 메타데이터 등을 담고 있는 디렉터리
- Dockerfile이 위치한 디렉터리, 디렉터리 내에 있는 파일을 전부 포함
* 주의: 이미지 빌드에 필요한 파일만 있는 것이 바람직함, 불필요한 파일이 있을 경우 빌드 시간이 느려지고 호스트의 메모리를 지나치게 점유할 수 있음
* `.dockerignore` 파일을 작성(<i>Dockerfile이 위치한 경로와 같은 곳에 작성</i>)하여 불필요한 파일이 빌드 되지 않게 방지 (`.gitignore` 와 유사)

##### Dockerfile을 이용한 컨테이너 생성과 커밋
1. 명령어가 실행될 때 마다 이전 Step에서 생성된 이미지에 의해 새로운 컨테이너가 생성
2. Dockerfile에 적힌 명령어를 수행하고 새로운 이미지 레이어로 저장

-> 이미지 빌드 완료 : Dockerfile의 명령어 줄 수 만큼 레이어가 존재, 컨테이너도 같은 수 만큼 생성되고 삭제됨

##### 캐시를 이용한 이미지 빌드
한 번 이미지 빌드를 마치고 난 뒤 다시 같은 빌드를 진행하면, 이전의 이미지 빌드에서 사용했던 캐시를 사용한다.

캐시 기능이 필요하지 않을 경우 -> `git clone`을 이용해서 빌드할 때! 업데이트 된 코드 대신 캐시된 코드가 불러와 진다.

- `--no-cache` : 기존 빌드에 사용된 캐시를 사용하지 않는 옵션
- `--cache-from`: 캐시로 사용할 이미지를 직접 지정하기

####2.4.3.3 멀티 스테이지를 이용한 Dockerfile 빌드하기
```
FROM golang
ADD main.go /root
WORKDIR /root
RUN go build -o /root/mainApp /root/main.go
CMD ["./mainApp"]
```
```
docker build . -t go_helloworld

docker images
REPOSITORY      TAG       IMAGE ID       CREATED         SIZE
go_helloworld   latest    042f77d02ad9   8 seconds ago   942MB
```
단순히 hello world 를 출력하는 이미지인데 무려 942mb!

- **멀티 스테이지** 빌드 방법 : 하나의 Dockerfile 안에 여러 개의 FROM 이미지를 정의함으로써 이미지의 크기를 줄이는 방법

```
// 빌드를 수행
FROM golang
ADD main.go /root
WORKDIR /root
RUN go build -o /root/mainApp /root/main.go

// 실행 파일만 복사하기
FROM alpine:latest
WORKDIR /root
COPY --from=0 /root/mainApp .
CMD ["./mainApp"]
```
- `--from=0` : 첫번째 FROM에서 빌드된 이미지의 최종 상태 
- 첫번째 FROM 이미지에서 빌드한 /root/mainApp 파일을 두 번째의 FROM에 명시된 이미지인 alpine:latest에 복사하는 것
- `alpine`: 우분투나 CentOS에 비해 이미지 크기가 매우 작지만 기본적인 런타임 요소가 포함되어 있는 리눅스 배포판 이미지

```
docker images
REPOSITORY      TAG           IMAGE ID       CREATED         SIZE
go_helloworld   multi-stage   38d555e13a06   3 seconds ago   7.36MB
go_helloworld   latest        042f77d02ad9   4 minutes ago   942MB
```
엄청난 용량 차이를 확인하였따..

### 2.4.4 기타 Dockerfile 명령어
- `ENV`: Dockerfile에서 사용될 환경변수를 지정한다.
    ```
   FROM ubuntu:14.04
   ENV test /home
  WORKDIR $test
  RUN touch $test/mytouchfile 
   ```
  - `${env_name:-value}`: env_name 환경변수값이 설정되지 않았으면 이 값은 `value` -> `env_name ? env_name : value `
  - `${env_name:+value}`: env_name 환경변수값이 설정되지 않았으면 이 값은 `빈 문자열`, 설정되어 있으면 `value` -> `env_name ? value : '' `
- `VOLUME`: 호스트와 공유할 컨테이너 내부의 디렉터리 설정
    ```
  VOLUME /home/volume
  VOLUME ["/home/dir", "home/dir2"] //json 형태도 가능
  VOLUME /home/dir /home/dir2
  ```
 - `ARG`: build 명령어를 실행할 때 추가로 입력을 받아 Dockerfile 내에 사용될 변수의 값을 설정
    ```
   ARG my_arg
   ARG my_arg2 = value2 // 기본값 설정
   ```
   ```
    docker build --build-arg my_arg=/home -t myarg:0.0 ./
    ```
   - `--build-arg`: ARG에 값 입력, `<키>=<값>` 형식으로 입력
  - `USER`: 컨테이너 내에 사용될 사용자 이름이나 UID를 설정하여 해당 사용자 권한으로 실행될 수 있게 한다.
    ```
    // 사용자의 그룹과 계정을 생성
    RUN groupadd -r author &&  useradd -r -g author alicek106
    // 사용자 설정
    USER alicek106
    ```
    - 기본적으로 root 사용자를 사용하도록 설정된다. -> 컨테이너가 호스트의 root 권한을 가질 수 있다는 것이기에 보안측면에서 바람직하지 않음
    - 가능하다면 USER를 사용하는 것을 권장한다.
    
  - `ONBUILD`: 빌드된 이미지를 기반으로 하는 다른 이미지가 생성될 때 실행할 명령어를 추가한다. 자식이미지에만 적용된다.
    ```
    // Dockerfile
    FROM ubuntu:14.04
    RUN echo "this is onbuild test!"
    ONBUILD RUN echo "onbuild!" >> /onbuild_file
    ```
    ```
    docker build ./ -t onbuild_test:0.0
    [+] Building 0.4s (6/6) FINISHED                                                                                           
     => [internal] load build definition from Dockerfile                                                                  0.0s
     => => transferring dockerfile: 227B                                                                                  0.0s
     => [internal] load .dockerignore                                                                                     0.0s
     => => transferring context: 2B                                                                                       0.0s
     => [internal] load metadata for docker.io/library/ubuntu:14.04                                                       0.0s
     => CACHED [1/2] FROM docker.io/library/ubuntu:14.04                                                                  0.0s
     => [2/2] RUN echo "this is onbuild test!"                                                                            0.3s
     => exporting to image                                                                                                0.0s
     => => exporting layers                                                                                               0.0s
     => => writing image sha256:d674af639b19f79acff6d97eca1cc9d870f27a97b22b2b0199489dc7d933112f                          0.0s
     => => naming to docker.io/library/onbuild_test:0.0                
    //** RUN echo "onbuild!" >> /onbuild_file 명령어에 대한 내용은 없다!   
    ```
    ```
    // Dockerfile2
    FROM onbuild_test:0.0
    RUN echo "this is child image!"
    ```
    ```
     docker build -f ./Dockerfile2 ./ -t onbuild_test:0.1
    [+] Building 0.7s (7/7) FINISHED                                                                                           
     => [internal] load build definition from Dockerfile2                                                                 0.0s
     => => transferring dockerfile: 98B                                                                                   0.0s
     => [internal] load .dockerignore                                                                                     0.0s
     => => transferring context: 2B                                                                                       0.0s
     => [internal] load metadata for docker.io/library/onbuild_test:0.0                                                   0.0s
     => [1/2] FROM docker.io/library/onbuild_test:0.0                                                                     0.0s
     
    
    // ***** 이제야 모습을 드러내었다!!!!!
    => [2/2] RUN echo "onbuild!" >> /onbuild_file                                                                        0.2s
     => [3/2] RUN echo "this is child image!"                                                                             0.3s
     => exporting to image                                                                                                0.0s
     => => exporting layers                                                                                               0.0s
     => => writing image sha256:b7bde3ff6518029a09d3c3f072a337b529c8684d06e3c2a6d5e3ab33e8c24242                          0.0s
     => => naming to docker.io/library/onbuild_test:0.1                                              
    ```
  - `STOPSIGNAL`: 컨테이너가 정지될 때 사용될 시스템 콜의 종류를 지정 
     - `SIGTERM`: 기본값
  
  - `HEALTHCHECK`: 컨테이너에서 동작하는 애플리케이션의 상태를 체크하도록 설정
    ```
    HEALTHCHECK /
    --interval=1m / 컨테이너 상태 체크 주기
    --timeout=3s / 상태 체크 명령어가 정한 시간을 초과하는지
    --retries=3 / 명령어 반복
    CMD curl -f http://localhost || exit 1 // 상태 체크 명령어
    ``` 
    -> 1분 마다 curl 명령어가 3초를 초과하지 않고 3번 반복이 가능해야함, 실패할 경우 `UNHEALTHY` 상태로 설정된다.
    
  - `SHELL`: 기본 셸은 리눅스는 `/bin/sh -c`, 윈도우는 `cmd /S /C` 이다.
    ```
    //node를 기본셸로 사용하고 싶을 때
    SHELL ["user/local/bin/node"]
    ```
 
  
  - `COPY`: 로컬 디렉터리에서 읽어 들인 컨텍스트로부터 이미지에 파일을 복사! **로컬만 가능**
    ```
    COPY test.html /home/
    COPY ["test.html", "/home/"]
    ``` 
  - `ADD`: COPY와 기능은 같지만, 외부 URL 및 tar 파일에서도 파일을 추가할 수 있는 차이점이 있다.
      ```
      ADD https://{URL} /home
      ADD test.tar /home 
      ```
    but! ADD는 그다지 권장하지 않는다. 외부 경로에서 정확히 어떤 파일이 추가될지 알 수 없기 때문.
    
  - `CMD`: 컨테이너가 시작될 때 실행할 명령어 설정러
      ```
    docker run -i -t  --name yes_entrypoint3 ubuntu:14.04 /bin/bash
    root@e4848cd443f5:/# 
    ```
  - `ENTRYPOINT`: 커맨드를 인자로 받아 사용할 수 있음
    ```
    docker run -i -t --entrypoint="echo" --name yes_entrypoint ubuntu:14.04 /bin/bash
    /bin/bash
    
    // CMD를 설정하지 않을 경우 에러 발생
    docker run -i -t --entrypoint="echo" --name yes_entrypoint ubuntu:14.04            
    docker: Error response from daemon: Conflict. The container name "/yes_entrypoint" is already in use by container "2c25b9a463bf2ea22768a7fa555b7f9f54d829c4defc6b47d50f7b378883ef81". You have to remove (or rename) that container to be able to reuse that name.
    ```
    
  ##### entrypoint를 이용한 스크립트 실행
  1. 어떤 설정 및 실행이 필요한지에 대해 스크립트로 정리
  2. ADD 또는 COPY로 스크립트를 이미지로 복사
  3. ENTRYPOINT를 이 스크립트로 설정 (컨테이너 내부에 포함되어야함)
  4. 이미지를 빌드해 사용
  5. 필요한 인자는 CMD로 전달
  
  ```
// Dockerfile
FROM ubuntu:14.04
RUN apt-get update
RUN apt-get install apache2 -y
ADD entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["bin/bash", "/entrypoint.sh"]
```
```
//entrypoint.sh

echo $1 $2 //CMD에서 받을 인
apachectl -DFOREGROUND
```
```
docker run -d --name entrypoint_apache_wever entrypoint_image:0.0 first second

docker logs entrypoint_apache_wever
first second
```

### 2.4.5 Dockerfile로 빌드할 때 주의할 점
**Dockerfile을 사용할 때의 좋은 습관**
1. 하나의 명령어를 \ 로 나눠서 가독성을 높이기
2. `.dockerignore` 파일을 작성해 불필요한 파일을 빌드 컨텍스트에 포함하지 않기
3. 빌드 캐시를 사용해 기존에 사용했던 이미지 레이어를 재사용하기

잘못된 Dockerfile 사용
```
FROM ubuntu:14.04
RUN mkdir /test
RUN fallocate -1 100m /test/dummy // 100M 크기의 가상 파일 만들기
RUN rm /test/dummy // 가상 파일 삭제
```
파일은 삭제되더라고 100M 크기의 가상파일이 이전 레이어에 남아있다. -> 이미지 레이어로 존재하기 때문제 저장공간은 차지하지만 의미 없는 저장공간이 되버린다.


방지하는 방법 : `&&` 로 각 RUN 명령어를 하나로 묶기! -> 이미지 레이어도 하나로 줄어든다
```
RUN mkdir /test && fallocate -1 100m /test/dummy &&  rm /test/dummy 
```
