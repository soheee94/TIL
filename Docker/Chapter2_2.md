# 02. 도커 엔진
## 2.2 도커 컨테이너 다루기
### 2.2.1 컨테이너 생성

1. 도커엔진 버전 확인
    ```cmd
    # docker -v
    Docker version 20.10.8, build 3967b7d
    ```
    도커는 다양한 기능이 빠르게 업데이트 되고, 새로운 버전이 배포되므로 설치된 버전을 확인하는 것은 매우 중요하다!

2. 컨테이너 생성
    ```cmd
    # docker run -i -t ubuntu:14.04
   
    // 로컬 도커엔진에 존재하지 않으므로 도커 허브(도커 중앙 이미지 저장소)에서 자동으로 이미지 내려받음
    Unable to find image 'ubuntu:14.04' locally
    14.04: Pulling from library/ubuntu
    2e6e20c8e2e6: Pull complete 
    0551a797c01d: Pull complete 
    512123a864da: Pull complete 
    Digest: sha256:43cb19408de1e0ecf3ba5b5372ec98978963d6d0be42d0ad825e77a3bd16b5f7
    Status: Downloaded newer image for ubuntu:14.04 
   
   // 컨테이너 내부로 들어옴
   root@b5169d77d1ca:/#
   // -> 기본 사용자@호스트 이름(무작위의 16진수 해시)
   ```
   - `docker run`: 컨테이너를 생성, 실행
   - `ubuntu:14.04`: 컨테이너를 생성하기 위한 이미지 이름
   - `-i`: 상호 입출력을 가능하게하는 옵션
   - `-t` : tty(Teletypewriter:  텍스트 전용 터미널)를 활성화해서 배시(bash)셸을 사용하도록 하는 옵션
   -  `docker run`에서 `-i` `-t` 중 하나라도 사용하지 않으면 셸을 정상적으로 사용할 수 없다.
 
 3. 컨테이너 정지 (빠져나오기)
 
    컨테이너 셸에서 `exit` 입력 또는 `Ctrl + D` 입력하면 된다.
    이 방법은 컨테이너 내부에서 빠져나오면서 동시에 **컨테이너를 정지** 시킨다!
     ```cmd
    root@b5169d77d1ca:/# exit
    exit 
    ```
    ** 정지시키지 않고 빠져나오는 방법은 `Ctrl + P, Q`를 입력한다. -> 정지시키지 않고, 단순히 컨테이너의 셸에서만 빠져나오기 때문에 앱을 개발하는 목적으로 컨테이너를 사용할 때는 이 방법을 사용한다!
    
  4. 이미지 내려 받기
     ```cmd
     // 이미지 내려받기 
     docker pull centos:7
     
     7: Pulling from library/centos
     2d473b07cdd5: Pull complete 
     Digest: sha256:0f4ec88e21daf75124b8a9e5ca03c37a5e937e0e108a255d890492430789b60e
     Status: Downloaded newer image for centos:7
     docker.io/library/centos:7기
     
     // 이미지 목록확인
     docker images
     REPOSITORY                  TAG       IMAGE ID       CREATED        SIZE
     hsh8616/docker101tutorial   latest    9b7730f38f40   4 days ago     28.2MB
     docker101tutorial           latest    9b7730f38f40   4 days ago     28.2MB
     alpine/git                  latest    b8f176fa3f0d   2 months ago   25.1MB
     ubuntu                      14.04     13b66b487594   4 months ago   197MB
     centos                      7         8652b9f0cb4c   9 months ago   204MB
     ```
     - `docker pull` : 이미지 내려받는 명령어 
     - `docker images` : 도커 엔진에 존재하는 이미지의 목록을 출력하는 명령어
    
 5. `create` 명령어로 컨테이너 생성
    ```cmd
    docker create -i -t --name mycentos centos:7
    // 컨테이너의 고유 Id
    d0353e4549c3abbd88815b2757c7111d6b46db2139332494d6cdf4dd4e658755
    ```
    - `--name`: 컨테이너의 이름 설정 (mycentos: 컨테이너 이름)
    - `docker inspect`: 컨테이너의 Id를 확인할 수 있는 명령어
    - **`run`명령어와 차이점**: `create` 명령어는 생성만 할 뿐 컨테이너로 들어가지 않는다
    
    ```cmd
    docker start mycentos
    mycentos
    docker attach mycentos
    [root@d0353e4549c3 /]# 
    ```
    - `docker start` : 컨테이너 시작하는 명령
    - `docker attach` : 컨테이너 내부로 들어가는 명령어어
    
   6. `run` vs `create` 
       - `run`: pull, create, start 명령어를 일괄적으로 실행 후 attach가 가능한 컨테이너라면 컨테이너 내부로 들어감 (-i, -t 옵션 사용했을 경우)
       - `create`: pull한 뒤 생성만 함
       
       -> 컨테이너를 생성함과 동시에 시작하기 때문에 <u>run 명령어를 더 많이 사용한다!</u>
       
       
 ### 2.2.2 컨테이너 목록 확인
`docker ps` : 정지되지 않은 컨테이너만 출력
 ```cmd
 docker ps             
CONTAINER ID   IMAGE      COMMAND       CREATED          STATUS          PORTS     NAMES
d0353e4549c3   centos:7   "/bin/bash"   38 minutes ago   Up 37 minutes             mycentos
 ```

`-a` : 정지된 컨테이너를 포함 모든 컨테이너를 출력하기 위한 옵션
 
 ```cmd
docker ps -a
CONTAINER ID   IMAGE          COMMAND       CREATED             STATUS                      PORTS     NAMES
d0353e4549c3   centos:7       "/bin/bash"   38 minutes ago      Up 38 minutes                         mycentos
b5169d77d1ca   ubuntu:14.04   "/bin/bash"   About an hour ago   Exited (0) 41 minutes ago             dazzling_rhodes
```

- `CONTAINER ID` : 컨테이너의 고유 ID (docker inspect 명령어로 전체 ID 확인 가능)
- `IMAGE`: 컨테이너를 생성할 때 사용된 이미지의 이름
- `COMMAND`: 컨테이너가 시작될 때 실행될 명령어
- `CREATED`: 생성되고 난 뒤 흐른 시간
- `STATUS`: 컨테이너의 상태, 실행 중임은 `Up`, 종료된 상태는 `Exited`, 일시 중지된 상태는 `Pause` 등으로 표기한다.
- `PORTS`: 컨테이너가 개방한 포트와 호스트에 연결한 포트를 나열
- `NAMES`: 컨테이너의 고유한 이름, --name 옵션으로 설정하지 않으면 도커엔진이 임의로 이름을 설정한다.

컨테이너 이름 변경하기 (angry_morse -> my_container)
```cmd
docker rename angry_morse my_container
```

### 2.2.3 컨테이너 삭제
`docker rm`: 컨테이너 삭제 (복구 안됨!)
```cmd
docker rm dazzling_rhodes
dazzling_rhodes
```
실행 중인 컨테이너는 삭제할 수 없으므로 컨테이너를 정지한 뒤 삭제하거나, 강제로 삭제해야한다.
```cmd
// 정지한 뒤 삭제
docker stop mycentos
docker rm mycentos

// 강제 삭제 옵션 추가
docker rm -f mycentos
```

모든 컨테이너를 삭제해야할 경우 `prune` 명령어 사용!
```cmd
docker container prune
```

### 2.2.4 컨테이너를 외부에 노출
컨테이너는 가상 머신과 마찬가지로 가상 IP 주소를 할당 받는다. 도커는 컨테이너에 `172.17.0.x`의 IP를 순차적으로 할당한다.

`ifconfig` : 컨테이너의 네트워크 인터페이스를 확인하는 명령어
```cmd
root@9007690f24f2:/# ifconfig
eth0      Link encap:Ethernet  HWaddr 02:42:ac:11:00:02  
          inet addr:172.17.0.2  Bcast:172.17.255.255  Mask:255.255.0.0
          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1
          RX packets:9 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:0 
          RX bytes:806 (806.0 B)  TX bytes:0 (0.0 B)

lo        Link encap:Local Loopback  
          inet addr:127.0.0.1  Mask:255.0.0.0
          UP LOOPBACK RUNNING  MTU:65536  Metric:1
          RX packets:0 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1000 
          RX bytes:0 (0.0 B)  TX bytes:0 (0.0 B)
```
- eth0 인터페이스: 172.17.0.2 할당
- lo 인터페이스: 로컬호스트

외부에서 접근하기 위해서는 eth0의 IP와 포트를 호스트의 IP와 포트에 바인딩해야한다.
```
docker run -i -t --name mywebserver -p 80:80 ubuntu:14.04
```
- `-p` : 컨테이너의 포트를 호스트의 포트와 바인딩해 연결할 수 있게 설정
    (`[호스트의 포트]:[컨테이너의 포트]`)
 
아파치 웹 서버는 기본적으로 80번 포트를 사용하므로 컨테이너의 80번 포트를 호스트와 연결한다.
아파치 웹 서버의 설치 및 실행이 완료되면 `[도커 엔진 호스트이 IP]:80`으로 접근한다! 
> 127.0.0.1:80 으로 접근함


