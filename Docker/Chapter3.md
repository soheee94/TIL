# 03. 도커 스웜
## 3.1 도커 스웜을 사용하는 이유
하나의 호스트 머신에서 도커 엔진을 구동하다가 CPU나 메모리, 디스크 용량과 같은 자원이 부족한 경우
- 가장 간단한 해답: 매우 성능이 좋은 서버를 새로 산다 -> 비용 측면에서 좋은 해답이 아님
- 가장 많이 사용하는 방법: 여러 대의 서버를 클러스터로 만들어 자원을 병렬로 확장하는 것

**but**, 여러대의 서버를 하나의 자원 풀로 만드는 것은 쉬운 작업이 아니다. 

문제점 
- 새로운 서버나 컨테이너가 추가됬을 때 발견하는 작업 필요
- 컨테이너를 어떤 서버에 할당 할서인가에 대한 스케줄러와 로드밸런스 문제
- 클러스터 내의 서버가 다운 됐을 때 고가용성(High Availability)을 어떻게 보장할지 등

대표적인 해결 방안은 도커에서 공식적으로 제공하는 <u>**도커 스웜(docker swarm)**</u>과 <u>**스웜 모드(swarm mode)**</u> 이다.

## 3.2 스웜 클래식과 도커 스웜 모드
도커 스웜의 장점
- 여러 대의 도커 서버를 하나의 클러스터로 만들어 컨테이너를 생성하는 여러기능을 제공한다.
- 다양한 전략을 세워 컨테이너를 특정 도커 서버에 할당 및 유동적으로 서버를 확장할 수 있다.
- 스웜 클러스터에 등록된 서버의 컨테이너를 쉽게 관리할 수 있다.

#### 도커 스웜의 종류

##### 1. 스웜 클래식 (Legacy)
- 도커 버전 1.6 이후 부터 사용할 수 있는 컨테이너로서의 스웜
- **목적**: 여러 대의 도커 서버를 하나의 지점에서 사용하도록 단일 접근점을 제공
- docker run, docker ps 등 일반적인 도커 명령어와 도커 API로 클러스터의 서버를 제어 및 관리
- 분산 코디네이터, 에이전트 등이 별도 실행

*분산 코디네이터(Distributed Coordinator): 클러스터에 영입할 새로운 서버의 발견, 클러스터의 각종 설정 저장, 데이터 동기화 등에 주로 이용 (ex. etcd, zookeeper, consul 등)

##### 2. 도커 스웜 모드
 - 도커 버전 1.12 이후부터 사용 가능
 - **목적**: 마이크로서비스 아키텍처의 컨테이너를 다루기 위한 클러스링 기능에 초점
 - 같은 컨테이너를 동시에 여러 개 생성해 필요에 따라 유동적으로 컨테이너의 수를 조절 가능
 - 컨테이너로의 연결을 분산하는 로드밸런싱 기능을 지원
 - 클러스터링을 위한 모든 도구가 도커 엔진 자체에 내장 되어 있어 쉽게 서버 클러스터 구축이 가능
 
 -> 서비스 확장성과 안정성 등 여러 측면에서 스웜 클래식보다 뛰어나기 때문에 일반적으로는 스웜 모드를 더 많이 사용한다.
 
## 3.3 스웜 모드
스웜 모드는 별도의 설치 과정이 필요하지 않으며 도커 엔진 자체에 내장되어있다.

도커 엔진의 스웜 모드 클러스터 정보 확인
```
 docker info | grep Swarm
 Swarm: inactive // 비활성 상태
```

### 3.3.1 도커 스웜 모드의 구조
> 도커 스웜 모드 = 매니저노드 + 워커 노드

- 워커 노드: 실제로 컨테이너가 생성되고 관리되는 도커 서버
- 매니저 노드: 워커 노드를 관리 하기 위한 도커 서버 (워커 노드의 역할 포함 -> 컨테이너 생성 가능)
- 매니저 노드는 1개 이상 있어야 하지만 워커 노드는 없을 수도 있다. -> 매니저 노드가 워커 노드의 역할도 포함하고 있기 때문

##### 권장 사항
- 워커 노드와 매니저 노드를 구분해서 사용
- 매니저 노드의 다중화 -> 매니저의 부하를 분산 시키고 특정 매니저 노드가 다운됐을 때 정상적으로 스웜 클러스터를 유지할 수 있기 때문
- 홀수 개의 매니저로 구성
    - 스웜 모드는 매니저 노드의 절반 이상에 장애가 생겨 정상적으로 작동하지 못할 경우 클러스터의 운영을 중단한다.
    - 네트워크 파티셔닝과 같은 현상이 발생했을 경우, 짝수 개의 매니저로 구성한 클러스터는 운영이 중단될 수도 있지만 홀수 개로 구성했을 경우에는 과반수 이상이 유지되는 쿼럼 매니저에서 운영을 계속할 수 있다. 
    

### 3.3.2 도커 스웜 모드 클러스터 구축
### 😢 실습 다시 해보기..

#### 매니저 노드의 IP 주소 입력 (다른 도커 서버가 매니저 노드에 접근하기 위한 IP 주소)
```
> docker swarm init --advertise-addr 192.168.0.100
Swarm initialized: current node (3l82vvc581b8myfrvptgbkvxd) is now a manager.

To add a worker to this swarm, run the following command:

    docker swarm join --token SWMTKN-1-25h3p12m90lv93l0arf9y0cq6z31a34nro5c3ngr9ysyoix18e-4me7bvztfjh9142g5oed1ibjj 192.168.0.100:2377

To add a manager to this swarm, run 'docker swarm join-token manager' and follow the instructions.
```
- `docker swarm join` 명령어: 새로운 워커 노드를 스웜 클러스터에 추가할 때 사용
- `--token`: 새로운 노드를 해당 스웜 클러스터에 추가하기 위한 비밀키
- 스웜 매니저는 기본적으로 `2377`번 포트를 사용 
- 매니저 노드는 일반적인 매니저 역할을 하는 노드와 리더 역할을 하는 노드로 구분
    - 리더 매니저: 모든 매니저 노드에 대한 데이터 동기화와 관리를 담당 -> 항상 작동할 수 있는 상태
    
#### 워커 노드 추가
```
// [위치: 워커 노드] 워커 노드 추가: 워커 노드로 사용할 각 서버에서 명령어 입력
docker swarm join --token 
```
```
// [위치: 매니저 노드] 매니저 노드에서 정상적으로 스웜 클러스터에 추가 됬는지 확인
docker node ls
```
토큰 확인
- `docker swarm join-token manager`: 매니저 노드를 추가하기 위한 토큰 확인
- `docker swarm join-toekn workder`: 워커 노드를 추가하기 위한 토큰 확
```
docker swarm join-token manager
To add a manager to this swarm, run the following command:

    docker swarm join --token SWMTKN-1-25h3p12m90lv93l0arf9y0cq6z31a34nro5c3ngr9ysyoix18e-3r33yom2avvbzpowo44vrf8lf 192.168.0.100:2377
```
토큰 갱신 (주기적으로 스웜 클러스터의 토큰을 변경하는 것이 보안 측면으로 안전)
- `swarm join` 명령어에 `--rotate` 옵션을 추가하고 변경할 토큰의 대상을 입력
```
// 매니저 노드를 추가하는 토큰을 변경
docker swarm join-token --rotate manager
```

#### 워커 노드 삭제
```
// [위치: 삭제하고자 하는 워커 노드]
docker swarm leave
```
- 매니저 노드는 해당 워커 노드의 상태를 `Down`으로 인지할 뿐 자동으로 워커노드를 삭제하지 않는다.
- `docker node rm` 명령어를 사용해 클러스터에서 워커 노드를 삭제한다.

#### 매니저 노드 삭제
```
docker swarm leave --force
```
- `--force` 옵션을 추가해야만 삭제할 수 있다.
- 매니저 노드를 삭제하면 해당 매니저 노드에 저장되있던 클러스터의 정보도 삭제 되므로 주의해야한다.
- 매니저 노드가 단 한 개 존재할 때 매니저 노드를 삭제하면 스웜 클러스터는 더이상 사용하지 못하는 상태가 된다 

#### 워커 노드 -> 매니저 노드
```
// [위치: 매니저 노드]
docker node promote swarm-worker1
```
swarm-worker1의 manger status 는 Reachable 이 된다.
#### 매니저 노드 -> 워커 노드
```
// [위치: 매니저 노드]
docker node demote swarm-worker1
```
- 매니저 노드가 1개일 때 demote 명령어 사용 불가
- 매니저 리더 노드에 demote 명령어를 사용하면 다른 매니저 노드 중 새로운 리더를 선출

### 3.3.3 스웜 모드 서비스
#### 3.3.3.1 스웜 모드 서비스 개념

현재까지 도커 명령어의 제어 단위는 `컨테이너` 이다. 그러나 스웜 모드에서 제어하는 단위는 컨테이너가 아닌 `서비스`이다!
- 서비스: 같은 이미지에서 생성된 컨테이너의 집합
    - 서비스를 제어하면 해당 서비스 내의 컨테이너에 같은 명령이 수행됨
    - 서비스 내에 컨테이너는 1개 이상 존재
    - 컨테이너들은 각 워커 노드와 매니저노드에 할당
    - 컨테이너 = 태스크 (Task)
    
- 롤링 업데이트(Rolling Update) : 서비스 내 컨테이너들의 이미지를 일괄적으로 업데이트 해야할 때 컨테이너들의 이미지를 순서대로 변경 -> 서비스 자체가 다운되는 시간 없이 컨테이너의 업데이트를 진행
    
**EX) ubuntu 이미지로 서비스를 생성하고 컨테이너의 수를 3개로 설정**

- 스웜 스케줄러는 서비스의 정의에 따라 컨테이너를 할당할 적합한 노드를 선정하고 해당 노드에 컨테이너를 분산해서 할당한다. -> 각 노드에 하나가 할당되지 않을 수도 있음
- 컨테이너 = `레플리카` (replica), 서비스에 설정한 레플리카의 수만큼 컨테이너가 스웜 클러스터 내에 존재해야함 (여기서는 레플리카셋이 3이다.)
- 스웜은 서비스의 컨테이너들에 대한 상태를 계속 확인하다가 서비스 내에 정의된 레플리카의 수만큼 컨테이너가 존재하지 않으면 새로운 컨테이너 레플리카 생성

#### 3.3.3.2 서비스 생성
**서비스를 제어하는 도커 명령어는 전부 매니저 노드에서만 사용 가능**

##### 첫번째 서비스 생성하기

```
docker service create ubuntu:14.04 /bin/sh -c "while true; do echo hello world; sleep 1; done"

ocl469vj3zaam71p46p3i40st
overall progress: 1 out of 1 tasks 
1/1: running   [==================================================>] 
verify: Service converged 
```
- `docker service create`: 도커 서비스 생성
- 서비스 내의 컨테이너는 `detached` 모드


```
docker service ls

ID             NAME             MODE         REPLICAS   IMAGE          PORTS
ocl469vj3zaa   hardcore_kirch   replicated   1/1        ubuntu:14.04   
```
- `docker service ls`: 스웜 클러스터 내의 서비스 목록
- 서비스의 이름을 따로 정의 하지 않아서 무작위로 설정
- `docker service rm`: 생성된 서비스 삭제 (컨테이너와 달리 상태에 관계 없이 서비스를 바로 삭제)

##### nginx 웹 서버 서비스 생성하기

```
docker service create --name myweb --replicas 2 -p 80:80 nginx
hoih8qinv6p6pac366fuce58j
overall progress: 2 out of 2 tasks 
1/2: running   [==================================================>] 
2/2: running   [==================================================>] 
verify: Service converged 
```
- `--replica`: 레플리카 갯수 옵션

```
docker service ps myweb
ID             NAME      IMAGE          NODE             DESIRED STATE   CURRENT STATE            ERROR     PORTS
2g4mgszhr3m3   myweb.1   nginx:latest   docker-desktop   Running         Running 22 seconds ago             
mlqaiy22j9pw   myweb.2   nginx:latest   docker-desktop   Running         Running 22 seconds ago

// replica = 2로 설정해서 컨테이너가 2개 생성된 것을 확인!        
```
- `docker servics ps [name]`: 생성된 서비스의 컨테이너

```
docker service scale myweb=4
myweb scaled to 4
overall progress: 4 out of 4 tasks 
1/4: running   [==================================================>] 
2/4: running   [==================================================>] 
3/4: running   [==================================================>] 
4/4: running   [==================================================>] 
verify: Service converged 


hansohee@SoHee-MacBookPro ~ % docker service ps myweb
ID             NAME      IMAGE          NODE             DESIRED STATE   CURRENT STATE            ERROR     PORTS
2g4mgszhr3m3   myweb.1   nginx:latest   docker-desktop   Running         Running 4 minutes ago              
mlqaiy22j9pw   myweb.2   nginx:latest   docker-desktop   Running         Running 4 minutes ago              
rfc3pbjhzund   myweb.3   nginx:latest   docker-desktop   Running         Running 10 seconds ago             
lth049m5attk   myweb.4   nginx:latest   docker-desktop   Running         Running 10 seconds ago        
```
- `docker service scale`: 레플리카셋의 수를 늘리거나 줄일 수 있다.

##### global 서비스 생성하기
서비스의 모드
1. 레플리카 모드(복제 모드): 레플리카셋의 수를 정의해 그만큼의 같은 컨테이너를 생성 / 실제 서비스를 제공하기 위해 일반적으로 쓰이는 모드
2. 글로벌 모드: 스웜 클러스터 내에서 사용할 수 있는 모든 노드에 컨테이너를 반드시! 하나씩 생성 / 스웜 클러스터를 모니터링하기 위한 에이전트 컨테이너 등을 생성할 때 유용

```
docker service create --name global_web --mode global nginx
wgg4xii2tkd52w9hz69wizh8h
overall progress: 1 out of 1 tasks 
3l82vvc581b8: running   [==================================================>] 
verify: Service converged 
```
- `--mode global`: 글로벌 모드 설정 (기본값: 복제모드)
```
// 모드 확인!
docker service ls
ID             NAME             MODE         REPLICAS   IMAGE          PORTS
wgg4xii2tkd5   global_web       global       1/1        nginx:latest   
ocl469vj3zaa   hardcore_kirch   replicated   1/1        ubuntu:14.04   
hoih8qinv6p6   myweb            replicated   4/4        nginx:latest   *:80->80/tcp

// 노드에 생성 확인!
hansohee@SoHee-MacBookPro ~ % docker service ps global_web
ID             NAME                                   IMAGE          NODE             DESIRED STATE   CURRENT STATE            ERROR     PORTS
3j93b4s2ztma   global_web.3l82vvc581b8myfrvptgbkvxd   nginx:latest   docker-desktop   Running         Running 23 seconds ago             
```

#### 3.3.3.3 스웜 모드의 서비스 장애 복구
myweb 서비스의 컨테이너 이름 확인!
```
docker ps
CONTAINER ID   IMAGE          COMMAND                  CREATED          STATUS          PORTS     NAMES
487038b7d8fe   nginx:latest   "/docker-entrypoint.…"   9 minutes ago    Up 9 minutes    80/tcp    global_web.3l82vvc581b8myfrvptgbkvxd.3j93b4s2ztmam5o8s0k0hel3j
789fa1faee43   nginx:latest   "/docker-entrypoint.…"   17 minutes ago   Up 17 minutes   80/tcp    myweb.3.rfc3pbjhzundvr85ufzljdwku
ca34cf73d630   nginx:latest   "/docker-entrypoint.…"   17 minutes ago   Up 17 minutes   80/tcp    myweb.4.lth049m5attkjgnjcdm7ev15i
0344cbee1158   nginx:latest   "/docker-entrypoint.…"   22 minutes ago   Up 22 minutes   80/tcp    myweb.1.2g4mgszhr3m3o8jzno3u72c9j
b3e14f926e3c   nginx:latest   "/docker-entrypoint.…"   22 minutes ago   Up 22 minutes   80/tcp    myweb.2.mlqaiy22j9pwc7v8ohffn6do5
ba45b4e260f6   ubuntu:14.04   "/bin/sh -c 'while t…"   28 minutes ago   Up 28 minutes             hardcore_kirch.1.r9n7km2qg9f3g8x2bchba01b2
```
myweb 서비스 중 1개 컨테이너 삭제
```
hansohee@SoHee-MacBookPro ~ % docker rm -f myweb.1.2g4mgszhr3m3o8jzno3u72c9j
myweb.1.2g4mgszhr3m3o8jzno3u72c9j
```
myweb 컨테이너 목록 확인 -> 새로운 컨테이너 생성 확인 가능
``` 
hansohee@SoHee-MacBookPro ~ % docker service ps myweb
ID             NAME          IMAGE          NODE             DESIRED STATE   CURRENT STATE            ERROR                         PORTS
h3eyfukvidsw   myweb.1       nginx:latest   docker-desktop   Running         Running 2 seconds ago                                  
2g4mgszhr3m3    \_ myweb.1   nginx:latest   docker-desktop   ** Shutdown        Failed 8 seconds ago     "task: non-zero exit (137)"   
mlqaiy22j9pw   myweb.2       nginx:latest   docker-desktop   Running         Running 22 minutes ago                                 
rfc3pbjhzund   myweb.3       nginx:latest   docker-desktop   Running         Running 17 minutes ago                                 
lth049m5attk   myweb.4       nginx:latest   docker-desktop   Running         Running 17 minutes ago         
```
복제모드로 설정된 서비스의 컨테이너가 정지하거나 특정 노드가 다운되면 스웜 매니저는 새로운 컨테이너를 새엇앻 자동으로 이를 복구하는 것을 확인!

#### 3.3.3.4 서비스 롤링 업데이트
- 롤링 업데이트(Rolling Update) : 서비스 내 컨테이너들의 이미지를 일괄적으로 업데이트 해야할 때 컨테이너들의 이미지를 순서대로 변경 -> 서비스 자체가 다운되는 시간 없이 컨테이너의 업데이트를 진행

최신 버전이 아닌 nginx 이미지로 서비스 생성
```
docker service create --name myweb2 --replicas 3 nginx:1.10
j4fcd6hvpd9wcp1yv2kqg5eqk
overall progress: 3 out of 3 tasks 
1/3: running   [==================================================>] 
2/3: running   [==================================================>] 
3/3: running   [==================================================>] 
verify: Service converged 
```

서비스의 이미지를 업데이트
```
docker service update --image nginx:1.11 myweb2
myweb2
overall progress: 3 out of 3 tasks 
1/3: running   [==================================================>] 
2/3: running   [==================================================>] 
3/3: running   [==================================================>] 
verify: Service converged 
```
- `--image`: 이미지 업데이트 옵션
- 한 번에 running 되는게 아니라 하나씩 running 실행됨

업데이트 변경 기록 확인
```
docker service ps myweb2
ID             NAME           IMAGE        NODE             DESIRED STATE   CURRENT STATE             ERROR     PORTS
sky973f29xj6   myweb2.1       nginx:1.11   docker-desktop   Running         Running 14 seconds ago              
kn14m7ffyivx    \_ myweb2.1   nginx:1.10   docker-desktop   Shutdown        Shutdown 14 seconds ago             
u4mby676lkf8   myweb2.2       nginx:1.11   docker-desktop   Running         Running 16 seconds ago              
ic7y7jgt6e0z    \_ myweb2.2   nginx:1.10   docker-desktop   Shutdown        Shutdown 21 seconds ago             
t6b7s84nxn05   myweb2.3       nginx:1.11   docker-desktop   Running         Running 12 seconds ago              
qw1kc9vg1i6o    \_ myweb2.3   nginx:1.10   docker-desktop   Shutdown        Shutdown 12 seconds ago      
```

롤링 업데이트 설정
```
docker service create
--replicas 4
--name myweb3
--update-delay 10s // 업데이트 시간 단위
--update-parallelism 2 // 한 번에 수행할 컨테이너 갯수 (기본값 1)
--updatte-failure-action continue // 업데이트 중 오류 발생 시 액션 (기본값 pause)
nginx:1.10
```

업데이트 롤백
```
docker service rollback myweb2
```
```
 docker service ps myweb2      
ID             NAME           IMAGE        NODE             DESIRED STATE   CURRENT STATE             ERROR     PORTS
jwjpxy0d9hw8   myweb2.1       nginx:1.10   docker-desktop   Running         Running 18 seconds ago              
sky973f29xj6    \_ myweb2.1   nginx:1.11   docker-desktop   Shutdown        Shutdown 18 seconds ago             
kn14m7ffyivx    \_ myweb2.1   nginx:1.10   docker-desktop   Shutdown        Shutdown 9 minutes ago              
yuede8e9dnhr   myweb2.2       nginx:1.10   docker-desktop   Running         Running 20 seconds ago              
u4mby676lkf8    \_ myweb2.2   nginx:1.11   docker-desktop   Shutdown        Shutdown 20 seconds ago             
ic7y7jgt6e0z    \_ myweb2.2   nginx:1.10   docker-desktop   Shutdown        Shutdown 9 minutes ago              
z14h8bei9o49   myweb2.3       nginx:1.10   docker-desktop   Running         Running 16 seconds ago              
t6b7s84nxn05    \_ myweb2.3   nginx:1.11   docker-desktop   Shutdown        Shutdown 17 seconds ago             
qw1kc9vg1i6o    \_ myweb2.3   nginx:1.10   docker-desktop   Shutdown        Shutdown 9 minutes ago     
```

#### 3.3.3.5 서비스 컨테이너에 설정 정보 전달하기: config, secret
- 스웜 모드에서 `config`, `secret`을 제공하는 이유 : 스웜 모드와 같은 서버 클러스터에서 파일 공유를 위해 설정 파일을 호스트마다 마련해두는 것은 매우 비효율적인 일이기 때문이고 (ex. DB 환경 변수 등), 민감한 정보를 환경 변수로 설정하는 것은 매우 바람직 하지 않음
- `config`, `secret` 은 스웜 모드에서만 사용될 수 있는 기능

##### secret 사용하기
secret 생성
```
echo 12345 | docker secret create my_mysql_password -
a41gtnqzo7qizlg5gb84o7k3i


docker secret ls
ID                          NAME                DRIVER    CREATED         UPDATED
a41gtnqzo7qizlg5gb84o7k3i   my_mysql_password             5 seconds ago   5 seconds ago
```
- `docker secret create` : secret 생성하기

secret 조회
```
docker secret inspect my_mysql_password
[
    {
        "ID": "a41gtnqzo7qizlg5gb84o7k3i",
        "Version": {
            "Index": 121
        },
        "CreatedAt": "2021-11-06T07:57:03.889891468Z",
        "UpdatedAt": "2021-11-06T07:57:03.889891468Z",
        "Spec": {
            "Name": "my_mysql_password",
            "Labels": {}
        }
    }
]
```
- secret 을 조회해도 실제 값을 확인할 수 없음
- secret 값은 매니저 노드 간에 암호화된 상태로 저장됨
- 메모리에 저장되기 때문에 서비스 컨테이너가 삭제되면 secret도 함께 삭제됨

secret을 통한 MYSQL 컨테이너 생성
```
 docker service create 
--name mysql 
--replicas 1 
--secret source=my_mysql_password,target=mysql_root_password 
--secret source=my_mysql_password,target=mysql_password 
-e MYSQL_ROOT_PASSWORD_FILE="/run/secrets/mysql_root_password" 
-e MYSQL_PASSWORD_FILE="/run/secrets/mysql_password" 
-e MYSQL_DATABASE="wordpress" 
mysql:5.7

phqz3r5n07gmfytbwaspbhhpu
overall progress: 1 out of 1 tasks 
1/1: running   [==================================================>] 
verify: Service converged 
```
/run/secrets 디렉터리에 파일 존재 확인
```
docker exec mysql.1.nosdp38c4mbdrjjixyc989nbp ls /run/secrets
mysql_password
mysql_root_password
```

파일 내용 확인 (설정한 secret 값 확인)
```
docker exec mysql.1.nosdp38c4mbdrjjixyc989nbp cat /run/secrets/mysql_password
12345
```

##### config 사용하기
```
docker config create registry-config config.yml
```


#### 3.3.3.6 도커 스웜 네트워크
```
docker network ls                                                            
NETWORK ID     NAME              DRIVER    SCOPE
2e6e0cfb6989   bridge            bridge    local
fbcdb1ce9048   docker_gwbridge   bridge    local //
78afd47071d5   host              host      local
z9c2419fcn2x   ingress           overlay   swarm //
9b63ad4874e4   mybridge          bridge    local
04a4eda8d6da   none              null      local
```
- `docker_gwbridge` : 스웜에서 오버레이(overlay) 네트워크를 사용할 때 사용
-  `ingress`: 로드밸런싱과 라우팅 메시(routing mesh)에 사용

##### ingress 네트워크
- 스웜 클러스터를 생성하면 자동으로 등록되는 네트워크
- 스웜 모드를 사용할 때만 유효
- 매니저 노드 뿐 아니라 스웜 클러스터에 등록된 노드라면 전부 생성됨
- 어떤 스웜 노드에 접근하더라도 서비스 내의 컨테이너에 접근할 수 있게 설정하는 라우팅 메시를 구성
- 서비스 내의 컨테이너에 대한 접근을 라운들 로빈 방식으로 분산하는 로드 밸런싱을 담당

##### overlay 네트워크
- 오버레이 네트워크는 여러 개의 도커 데몬을 하나의 네트워크 풀로 만드는 가상화 기술의 하나
- ingress 네트워크는 오버레이 네트워크 드라이버! 를 사용
- 여러 도커 데몬에 존재하는 컨테이너가 서로 통신할 수 있다.

##### docker_gwbridge 네트워크
- 외부로 나가는 통신 및 오버레이 네트워크의 트래픽 종단점(VTEP) 역할을 담당
- 컨테이너 내부의 네트워크 인터페이스 카드 중 eth1과 연결됨

#### 3.3.3.7 서비스 디스커버리
서비스 디스커버리: 컨테이너 생성의 발견 혹은 없어진 컨테이너의 감지 
- 일반적으로 이 동작은 주키퍼, etcd 등 분산 코디네이터를 외부에 두고 사용해서 해결하지만, **스웜 모드는 자체적으로 지원한다.**
- 오버레이 네트워크를 사용하는 서비스에 대해 작동한다.

서비스 발견의 예시


1. 오버레이 네트워크 생성
    ```
    docker network create -d overlay discovery
    u09cx4pxchvocr3rrz7d0jca9
    ```

2. server 서비스(replica=2)와 client 서비스 생성
    ```
    docker service create --name server --replicas 2 --network discovery alicek106/book:hostname
    t95f819d65dnp8nqvrg42m92q
    overall progress: 2 out of 2 tasks 
    1/2: running   [==================================================>] 
    2/2: running   [==================================================>] 
    verify: Service converged 
    
    docker service create --name client2 --network discovery alicek106/book:curl ping docker.com
    zdimyysfj4ackwrbmqhezckn9
    overall progress: 1 out of 1 tasks 
    1/1: running   [==================================================>] 
    verify: Service converged 
    ```
   
3. client 컨테이너 ID 확인한 뒤 컨테이너 내부로 들어감
    ```
   docker ps --format "table {{.ID}}\t{{.Command}}" | grep ping
   c084cc2035d4   "ping docker.com"
   
   hansohee@SoHee-MacBookPro ~ % docker exec -it c084cc2035d4 bash
   ```
   
4. curl 명령어로 server 컨테이너에 접근하기
    ```
   root@c084cc2035d4:/# curl -s server | grep hello
   root@c084cc2035d4:/# curl -s server | grep Hello
   	<p>Hello,  5fa8ec3bc9ca</p>	</blockquote>
   root@c084cc2035d4:/# curl -s server | grep Hello
   	<p>Hello,  36b8c974df95</p>	</blockquote>
   root@c084cc2035d4:/# curl -s server | grep Hello
   	<p>Hello,  5fa8ec3bc9ca</p>	</blockquote>
   root@c084cc2035d4:/# curl -s server | grep Hello
   	<p>Hello,  36b8c974df95</p>	</blockquote>
   root@c084cc2035d4:/# 
   ```
   - 명령어를 보낼 때 마다 다른 컨테이너에 접근하는 것을 확인
   - server 서비스에 들어있는 2개의 컨테이너 IP 중 하나를 반환 (라운드 로빈 방식)
   
#### 3.3.3.7 스웜 모드 볼륨
1. volume 타입의 볼륨 생성
    ```
    docker service create --name ubuntu
    --mount type=volume,source=myvol target=/root
    ```
    - `--mount` 옵션의 `type` 값에 volume 으로 지정해야함
    - `source` 값은 사용할 볼륨 이름 -> 해당 이름의 볼륨이 없을 경우 새로 생성함
    - `source` 옵션을 명시하지 않으면 임의의 이름을 가진 볼륨 생성
    - `target`: 컨테이너 내부에 마운트 될 디렉터리의 위치
    - `volume-nocopy` 옵션을 추가하면 파일들이 볼륨에 복사되지 않도록 설정할 수 있다.

2. bind 타입의 볼륨 생성
    ```
    docker service create --name ubuntu
    --mount type=bind,source=/root/host target=/root
    ```
    - 호스트와 디렉터리를 공유할 때 사용
    - `--mount` 옵션의 `type` 값에 bind 로 지정해야함
    - 호스트 디렉터리를 설정해야 하므로 source 옵션을 반드시 명시해야함


**스웜 모드에서 볼륨의 한계점**

어느 노드에 할당해도 서비스에 정의된 볼륨을 사용할 수 있어야 한다. -> 스웜 클러스터에서 볼륨을 사용하기란 까다롭다.

-> 해결하기 위한 일반적인 방법

1. 어느 노드에서도 접근이 가능한 퍼시스턴트 스토리지(Persistent Storage) 사용
2. 각 노드에 라벨을 붙여 서비스에 제한을 설정하는 방법

### 3.3.4 도커 스웜 모드 노드 다루기
    