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
-> 127.0.0.1:80 으로 접근함!

> 호스트 IP의 80번 포트로 접근 -> 80번 포트는 컨테이너의 80번 포트로 포워딩 -> 웹 서버 접근

### 2.2.5 컨테이너 애플리케이션 구축

**한 컨테이너에 프로세스 하나만 실행하는 것**이 도커의 철학! (ex: 데이터베이스와 웹 서버 컨테이너를 구분)

1. MySQL이미지를 사용해 데이터 베이스 컨테이너 만들기
    ```
   docker run -d --name wordpressdb -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=wordpress mysql:5.7
   ```
2. 워드프레스 이미지를 이용해 워드프레스 웹 서버 컨테이너 생성하기
    ```
   docker run -d -e WORDPRESS_DB_HOST=mysql -e WORDPRESS_DB_USER=root -e WORDPRESS_DB_PASSWORD=password --name wordpress --link wordpressdb:mysql -p 80 wordpress
   ``` 
3. `docker ps` 명령어로 어느 포트와 연결됬는지 확인하기
    ```
   CONTAINER ID   IMAGE       COMMAND                  CREATED              STATUS              PORTS                   NAMES
   48e5eb2f6670   wordpress   "docker-entrypoint.s…"   4 seconds ago        Up 3 seconds        0.0.0.0:54714->80/tcp   wordpress
   ```
   또는 `docker port {컨테이너_이름}`
   ```
    docker port wordpress
    80/tcp -> 0.0.0.0:54714
    ```
   > -p 80과 같이 입력하면 컨테이너의 80번 포트를 쓸 수 있는 호스트의 포트 중 하나와 연결하기 때문에, 어느 포트와 연결됬는지 확인이 필요하다.

4. `127.0.0.1:54714` 로 접속하면 워드프레스 웹 서버에 접근 가능!

**사용한 run 명령어 옵션들**
- `-d`: `-i -t`가 컨테이너 내부로 진입하도록 attach 가능한 상태로 설정한다면, `-d`는 Detached 모드로 컨테이너를 실행한다. Detached 모드는 컨테이너를 백그라운드에서 동작하는 애플리케이션으로써 실행하도록 설정한다. 입출력이 없는 상태로 컨테이너를 실행하고, 컨테이너 내부에서 프로그램이 터미널을 차지하는 포그라운드(*)로 실행되 사용자의 입력을 받지 않는다.

    - foreground process : 쉘(shell)에서 해당 프로세스 실행을 명령한 후, 해당 프로세스 수행 종료까지 사용자가 다른 입력을 하지 못하는 프로세스
    - background process : 사용자 입력과 상관없이 실행되는 프로세스
    
    - ubuntu 이미지로 만든 컨테이너를 -d 로 설정하고 실행하면 포그라운드로 동작하는 프로그램이 없어 종료됨
    - mysql 컨테이너를 -i -t로 설정하고 실행하면 포그라운드로 실행된 로그를 볼 수 있고, 상호입출력이 불가능함
 - `-e` : 컨테이너 내부의 환경변수를 설정한다.
    - `-d` 옵션으로 생성된 컨테이너에서 echo 명령어를 사용하여 설정한 환경변수를 확인하는 방법 
        ```
        docker exec -i -t wordpressdb /bin/bash
       root@52ebde60680a:/# echo $MYSQL_ROOT_PASSWORD
       password
       ```
      `exec` 명령어: 컨테이너 내부에서 명령어를 실행한 뒤 결과값을 반환 받을 수 있다.
 - `--link`: 내부 IP를 알 필요 없이 항상 컨테이너에 별명으로 접근하도록 설정 (deprecated된 옵션이며 추후 삭제 가능성 -> 도커 브리지 네트워크(2.2.7.2) 사용 권장)
 
    내부 IP는 컨테이너를 시작할 때마다 재할당하기 때문에 매번 변경 되는 컨테이너의 IP로 접근하기 어렵다. 
    ```
   --link wordpressdb:mysql
   ```
   wordpress 컨테이너는 wordpressdb의 IP를 몰라도 mysql이라는 호스트명으로 접근이 가능하다.
   * 주의점: --link에 입력된 컨테이너가 실행 중이지 않거나 존재하지 않는다면 실행하려는 컨테이너 또한 실행할 수 없다. -> 실행 순서의 의존성도 정의
   
 ### 2.2.6 도커 볼륨
 
 도커의 컨테이너는 생성과 삭제가 매우 쉬워서 실수로 컨테이너를 삭제하면 데이터를 복구할 수 없게 된다. 이를 방지하기 위해 컨테이너의 데이터를 <u>영속적 데이터로 활용</u>할 수 있는 방법이 몇 가지 있는데, 그 중 가장 활용하기 쉬운 방법이 **볼륨**을 활용하는 것이다.
 
 #### 1. 호스트 볼륨 공유
 ```
docker run -d --name wordpressdb_hostbolume -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=wordpress -v /home/wordpress_db:/var/lib/mysql mysql:5.7
```

`-v` 옵션 : `[호스트의 공유 디렉터리]:[컨테이너의 공유 디렉터리]`형태로, 위에서는 호스트의 /home/wordpress_db 디렉터리와 컨테이너의 /var/lib/mysql 디렉터리를 공유한다는 뜻
 - 호스트의 공유 디렉터리가 없어도 자동으로 이를 생성해준다.
 - 컨테이너를 삭제해도, 호스트의 디렉터리에 데이터가 존재한다.
 - 디렉터리 단위의 공유 뿐 아니라, 단일 파일 단위의 공유도 가능하며 동시에 여러개의 -v 옵션을 쓸 수 있다.
 - 호스트의 공유 디렉터리가 이미 존재하는 경우, 컨테이너의 디렉터리가 덮어씌워진다. 
 
 #### 2. 볼륨 컨테이너
```
docker run -i -t \
--name volumes_from_contianer \
--volumes-from volume_overide \
ubuntu:14.04
```

`--volumes-from` 옵션 : `-v` 또는 `--volume` 옵션을 적용한 컨테이너의 볼륨 디렉터리를 공유할 수 있다.
- 위의 예제는 volume_overide 컨테이너에서 볼륨을 공유 받고 있다.
- volume_overide 컨테이너는 \home\testdir_2 디렉터리를 호스트와 공유하고 있으며, 이 컨테이너를 볼륨 컨테이너로서 volumes_from_container 컨테이너에 다시 공유하고 있다.

- 데이터 볼륨은 컨테이너와 호스트 사이의 디렉터리를 공유하는 것이었으나, 데이터 볼륨 컨테이너는 컨테이너 간에 <u>디렉터리를 공유</u>한다.
- 데이터 볼륨 컨테이너는 이름 그대로 **데이터를 저장하는 것**만이 목적인 컨테이너다. 디스크에 저장된 컨테이너가 갖는 퍼시스턴스 데이터를 볼륨으로 만들어 다른 컨테이너에 공유하는 컨테이너가 볼륨 컨테이너다.
- 볼륨 컨테이너가 직접 볼륨을 다뤄주므로 볼륨을 필요로 하는 컨테이너가 사용할 호스트 디렉터리를 알 필요가 없고 디렉터리를 제공하는 볼륨 컨테이너만 지정 하면된다. (데이터 볼륨이 데이터 볼륨 컨테이너 안에 **캡슐화**되므로 호스트에 대해 아는 것이 없어도 데이터 볼륨을 사용할 수 있다.)

출처: https://cornswrold.tistory.com/491 [평범한개발자노트]

#### 3. 도커 볼륨
도커 컨테이너를 통해 데이터를 보존하는 것도 나쁘지 않지만, 도커 자체에서 제공하는 볼륨 기능을 활용해 데이터를 보존할 수도 있다.

myvolume 이라는 볼륨 생성
```
docker volume create --name myvolume
```

myvolume 볼륨을 사용하는 컨테이너 생성
```
docker run -i -t --name myvolume_1 -v myvolume:/root/ ubuntu:14.04
```
- `-v` 옵션 사용 형식(호스트 볼륨 공유 형식과 다름!) : `[볼륨의 이름]:[컨테이너의 공유 디렉터리]`
- 볼륨을 컨테이너의 /root/ 디렉터리에 마운트 한다.
- 볼륨은 디렉터리 하나에 상응하는 단위로서 도커엔진에서 관리한다.
- `docker inspect` 명령어: 도커의 모든 구성 단위의 정보를 확인할 때 사용한다.

`-v` 옵션으로 도커 자동 생성하기
```
docker run -i -t --name volume_auto -v /root uvuntu:14.04
``` 
- `-v` 옵션 사용 형식: `[컨테이너에서 공유할 디렉터리 위치]`
- docker volume ls 명령어로 확인하면 이름이 무작위의 16진수 형태인 볼륨이 자동 생성 확인 가능


스테이트리스(stateless) 
- 컨테이너가 아닌 외부에 데이터를 저장하고 컨테이너는 그 데이터로 동작하도록 설계하는 것
- 컨테이너가 삭제되도 데이터는 보존되므로 도커를 사용할 때 매우 바람직한 설계

vs

스테이트풀(stateful)
- 컨테이너가 데이터를 저장하고 있는 경우
- 지양하는 것이 좋다

### 2.2.7 도커 네트워크
#### 2.2.7.1 도커 네트워크 구조
- `veth`: 컨테이너를 시작할 때 마다 호스트에 `veth-`(virtual eth)라는 네트워크 인터페이스를 생성함으로써 내부 IP와 외부망을 연결한다! (컨테이너의 eth0 인터페이스와 연결) 
- `docker0` 브리지: 각 veth 인터페이스와 바인딩되어 호스트의 eth0 인터페이스와 이어주는 역할
<img src="https://jonnung.dev/images/docker_network.png"/>

#### 2.2.7.2 도커 네트워크 기능
컨테이너를 생성하면 기본적으로 docker0 브리지를 통해 외부와 통신할 수 있는 환경을 사용할 수 있지만, 사용자의 선택에 따라 여러 네트워크 드라이버를 쓸 수 있다.
도커가 제공하는 대표적인 네트워크 드라이버로는 브리지, 호스트, 논, 컨테이너, 오버레이가 있다.

##### 브리지 네트워크
: docker0 이 아닌 사용자 정의 브리지를 새로 생성해 각 컨테이너에 연결하는 네트워크 구조

새로운 브리지 네트워크 생성
```
docker network create --driver bridge mybridge
9b63ad4874e40f899d003ec4d712ffe4ed0e507ee054698b84a9919b7463ab68
```
mybridge 네트워크를 사용하는 컨테이너 생성
```
docker run -i -t --name mynetwork_container --net mybridge ubuntu:14.04
```
ifconfig로 확인하면 새로운 IP 대역이 할당된 것을 확인할 수 있다. 브리지 타입의 네트워크를 생성하면 도커는 IP 대역을 차례대로 할당한다.
```
root@978cfeaf1f62:/# ifconfig
eth0      Link encap:Ethernet  HWaddr 02:42:ac:12:00:02  
          inet addr:172.18.0.2  Bcast:172.18.255.255  Mask:255.255.0.0
```

사용자 정의 네트워크는 docker network connet, disconnect를 통해 컨테이너에 유동적으로 붙이고 뗄 수 있다.
브리지 네트워크, 오버레이 네트워크와 같이 특정 IP 대역을 갖는 네트워크 모드에만 사용할 수 있다!

#### 호스트 네트워크
: 호스트의 네트워크 환경을 그대로 쓸 수 있는 네트워크 구조 
```
docker run -i -t --name network_host --net host ubuntu:14.04
root@docker-desktop:/# 
```
- 호스트 머신에서 설정한 호스트 이름도 컨테이너가 물려받기 때문에 컨테이너의 호스트 이름도 호스트 머신의 호스트이름으로 설정된다.
- 컨테이너 내부의 애플리케이션을 별도의 포트 포워딩 없이 바로 서비스할 수 있다.

#### 논 네트워크
: 아무런 네트워크를 쓰지 않는 구조

```
docker run -i -t --name network_none --net none ubuntu:14.04 
root@d93e4ca5ad7c:/# ifconfig
lo        Link encap:Local Loopback  
          inet addr:127.0.0.1  Mask:255.0.0.0
          UP LOOPBACK RUNNING  MTU:65536  Metric:1
          RX packets:0 errors:0 dropped:0 overruns:0 frame:0
          TX packets:0 errors:0 dropped:0 overruns:0 carrier:0
          collisions:0 txqueuelen:1000 
          RX bytes:0 (0.0 B)  TX bytes:0 (0.0 B)
```

네트워크 인터페이스를 확인하면 로컬호스트(lo) 외에는 존재하지 않는다.

#### 컨테이너 네트워크
: 다른 컨테이너의 네트워크 네임스페이스 환경을 공유하는 네트워크 구조
(공유되는 속성: 내부 IP, 네트워크 인터페이스의 맥 주소 등)

```
docker run -i -t -d --name network_container_1  ubuntu:14.04
docker run -i -t -d --name network_container_2 --net container:network_container_1 ubuntu:14.04
```
- `--net` 옵션 값 : `container:[다른 컨테이너의 ID]`
- `-i -t -d` 를 함께 사용하면 컨테이너 내부에서 셸을 실행하지만 내부로 들어가지 않으며 컨테이너가 종료되지 않는다! (테스트 용으로 생성할 때 유용)
- network_container_2는 내부 IP를 새로 할당받지 않으며, 네트워크 관련 사항은 network_container_1와 모두 동일하다.

#### 브리지 네트워크와 --net-alias
: 브리지 타입의 네트워크와 run 명령어의 --net-alias 옵션을 함께 쓰면 특정 호스트 이름으로 컨테이너 여러개에 접근할 수 있다.
```
docker run -i -t -d --name network_alias_container1 --net mybridge --net-alias alicek106 ubuntu:14.04
fb955e0082be74741c33d4302cce659aa37e6b2e72be60fc78baea4a747b3305

docker run -i -t -d --name network_alias_container2 --net mybridge --net-alias alicek106 ubuntu:14.04
b3b7965eea564d1c80fcef18313c55748f91581e83e138380380cef589eb5124

docker run -i -t -d --name network_alias_container3 --net mybridge --net-alias alicek106 ubuntu:14.04
```
다른 컨테이너에서 alicek106 이라는 호스트 이름으로 위의 3개의 컨테이너에 접근할 수 있다. 
세개의 컨테이너에 접근할 컨테이너를 생성한 뒤 alicek106 이라는 호스트 이름으로 ping 요청을 전송하면 컨테이너 3개의 IP로 각각 ping 전송이 된다!
```
docker run -i -t --name network_alias_ping --network mybridge ubuntu:14.04
root@57943d24b3ee:/# ping -c 1 alicek106
PING alicek106 (172.18.0.3) 56(84) bytes of data.
64 bytes from network_alias_container2.mybridge (172.18.0.3): icmp_seq=1 ttl=64 time=4.45 ms
```
매번 달라지는 IP를 결정하는 것은 라운드 로빈 방식이다.

- 라운드 로빈 : 하나의 중앙처리장치를 여러 프로세스들이 우선순위 없이 돌아가며 할당받아 실행되는 방식

#### MacVLAN 네트워크
MacVLAN은 호스트의 네트워크 인터페이스 카드를 가상화해 물리 네트워크 환경을 컨테이너에 동일하게 제공한다. 따라서 MacVLAN을 사용하면 컨테이너는 물리 네트워크 상에서 가상의 맥주소를 가지며, 해당 네트워크에 연결된 다른 장치와의 통신이 가능해진다.


### 2.2.8 컨테이너 로깅
#### json-file 로그 사용하기
`docker logs`: 컨테이너의 표준 출력을 확인할 수 있는 명령어
- `--tail [출력할 줄 수]` 옵션: 마지막 로그 줄 부터 출력할 줄의 수를 설정
- `--since [유닉스 시간]` 옵션: 특정 시간 이후의 로그를 확인
- `-t` 옵션: 타임스태프 표시
- `-f` 옵션: 실시간으로 출력내용 확인

컨테이너 로그는 json 형태로 도커 내부에 저장된다. 아래와 같은 경로로! 
```
cat /var/lib/docekr/containers/${CONTAINER_ID}/${CONTAINER_ID}-json.log
```

컨테이너 로그가 너무 많은 상태로 방치하면, json 파일의 크기가 계속해서 커지기 때문에 이를 방지하기 위해서 `--log-opt` 옵션으로 json 로그 파일의 최대 크기를 지정할 수 있다. 
```
docker run -it
--log-opt max-size=10k --log-opt max-file=3
--name log-test ubuntu:14.04
```
아무런 설정도 하지 않는다면 컨테이너 로그를 JSON파일로 저장한다.

#### syslog 로그
syslog: 유닉스 계열 운영체제에서 로그를 수집하는 오래된 표준 중 하나
```
docker run -d --name syslog_container
--log-driver=syslog
ubuntu:14.04
echo syslogtest
```

#### fluentd 로깅
fluentd는 각종 로그를 수집하고 저장할 수 있는 기능을 제공하는 오픈소스 도구로서, 도커 엔진의 컨테이너 로그를 fluented를 통해 저장할 수 있도록 플러그인을 공식적으로 제공한다.
JSON 을 사용하기 때문에 쉽게 사용할 수 있고, 수집되는 데이터를 AWS S3, HDFS, MongoDB 등 다양한 저장소에 저장할 수 있다.

### 2.2.9 컨테이너 자원 할당 제한
컨테이너의 자원 할당량을 조정하도록 옵션을 입력할 수 있다. 자원 할당 옵션을 설정하지 않으면 호스트의 자원을 전부 점유해 다른 컨테이너들뿐 아니라 호스트 자체의 동작이 멈출 수 있다.
docker inspect 명령어로 설정된 자원 제한을 확인할 수 있다.

#### 컨테이너 메모리 제한
`--memory` 옵션으로 컨테이너의 메모리를 제한할 수 있다.
- 단위: m(megabyte), g(gigabyte)
- 최소 메모리: 4mb

```
docker run -d
--memory=200m
--memory-swap=500m
--name memory_1g
nginx
```

- 프로세스가 컨테이너에 할당된 메모리를 초과하면 자동으로 종료된다.
- swap 메모리(가상 메모리: RAM에 용량이 부족할 경우 프로세스가 임시 저장되는 공간)는 메모리의 2배로 설정되지만, `--memory-swap` 옵션으로 지정할 수 있다.

#### 컨테이너 CPU 제한
1. --cpu-shares : 시스템에 존재하는 CPU를 어느 비중만큼 나눠 쓸 것인지 명시하는 옵션
    ```
    docker run -i -t --name cpu_share
   --cpus-shares 1024
   ubuntu:14.04
   ```
   - 상대적인 값을 가지고, 아무런 설정을 하지 않았을 때 1024의 값을 가지고 CPU 할당에서 1의 비중을 뜻한다.

2. --cpuset-cpu : CPU가 여러개 있을 때 컨테이너가 특정 CPU만 사용하도록 설정
3. --cpu-period, --cpu-quota : 스케줄링 주기를 변경하여 설정 (주기는 기본적으로 100ms로 설정)
    ```
   docker run -d --name quota_1_4
   --cpu-period=100000 //100ms
   --cpu-quota=25000
   ```
   - `[--cpu-quota 값]/[--cpu-period 값]` 만큼 CPU 시간을 할당받는다.
   - 위의 예제는 일반적인 컨테이너보다 CPU 성능이 1/4 정도로 감소한다.
4. --cpus : --cpu-period, --cpu-quota와 동일한 기능을 하지만 직관적으로 CPU의 개수를 직접 지정한다.
    ```
   docker run -d --name cpus_container
   --cpus=0.5
   ```
   - CPU 성능이 1/2로 감소한다.
   
#### BLOCK I/O 제한
컨테이너를 생성할 때, 파일을 읽고쓰는 대역폭 제한이 설정되지 않는다. 하나의 컨테이너가 블록 입출력을 과도하게 사용하지 않게 하려면 --device-write-bps, --device-read-bps, --device-write-iops, device-read-iops 옵션을 지정해 블록 입출력을 제한한다.
단, Direct I/O의 경우에만 블록 입출력이 제한되고, Buffered I/O는 제한하지 않는다.
