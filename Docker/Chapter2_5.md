# 02. 도커 엔진
## 2.5 도커 데몬

: macOS에서 실습이 불가 -> 우선은 알아두자! 

### 2.5.1 도커의 구조

- 도커 서버: 컨테이너를 생성하고 실행하며 이미지를 관리하는 주체
    - dockerd 프로세스로서 동작 
    - 도커 데몬: 도커 프로세스가 실행되어 서버로서 입력을 받을 준비가 된 **상태**, 외부에서 API 입력을 받아 도커엔진의 기능을 수행
- 도커 클라이언트: API를 사용할 수 있도록 CLI를 제공하는 것

**도커 제어 프로세스**
1. 사용자가 `docker version` 같은 명령어 입력
2. `/user/bin/docker`는 `/var/run/docker.sock` 유닉스 소켓을 사용해 도커 데몬에게 명령어를 전달
3. 도커 데몬은 이 명령어를 파싱하고 명령어에 해당하는 작업을 수행
4. 수행 결과를 도커 클라이언트에게 반환하고, 사용자에게 결과를 출력


### 2.5.2 도커 데몬 실행
```
service docker start
service docker stop
->command not found: service
```
-> service 자체가 없는 command라고 나와서 확인 불가
```
dockerd 
```
dockerd를 입력하면 도커 데몬이 실행된다고 한다..
-> 도커 데몬을 직접 실행하면 하나의 터미널을 차지하는 foreground 상태로 실행되기 때문에 운영 및 관리 측면에서 바람직하지 ㅇ낳다. 실제 운영환경에서는 리눅스 서비스로서 관리 하는 것이 좋다!

### 2.5.3 도커 데몬 설정기

#### 도커 데몬 제어: `-H`
도커 데몬의 API 를 사용할 수 있는 방법을 추가한다.
```
dockerd
dockerd -H unix:///var/run/docker.sock **(DEFAULT)**
```
두 개의 명령어는 같은 값 
```
dockerd -H tcp://192.168.99.100:2375
```
`192.168.99.100:2375` 포트로 도커 데몬을 바인딩


#### 도커 데몬에 보안적용: `--tlsverify`
도커를 설치하면 기본적으로 보안연결이 설정돼 있지 않다.
- 도커 클라이언트, remote API 를 사용할 때 별도의 보안이 적용되지 않음을 의미
- 보안을 적용하지 않는 것은 바람직 하지 않으므로, 도커 데몬에 TLS 보안을 적용하고, 도커 클라이언트와 Remote API 클라이언트가 인증되지 않으면 도커 데몬에 제어할 수 없도록 한다.
- 서버, 클라이언트에서 각각 작업이 필요함
```
dockerd --tlsverify
--tlscacert=/root/.dcoekr/ca.pem
--tlscert=/root./docker/server-cert.pem
--tlskey=/root/.docker/server-key.pem
```
- 보안이 적용된 도커 데몬을 사용하려면 ca.pem, key.pem, cert.pem 파일이 필요

#### 도커 스트리지 드라이버 변경: `--storage-driver`
현재 사용하고 있는 스토리지 드라이버 확인하기
```
docker info | grep "Storage Driver"
Storage Driver: overlay2
```
- 도커를 사용하는 환경에 다라 스토리지 드라이버는 자동으로 정해진다 -> 우분투 같은 데비안 계열 운영체제는 `overlay2`, CentOS와 같은 운여에제는 `deviceampper`를 사용하는것이 대표적인 예이다.
- `--storage-driver` 옵션으로 스토리지 드라이버 선택
- 지원하는 드라이버: OverlayFS, AUFS, Btrfs, Devicemapper, VFS, ZFS
- 애플리케이션 및 개발 환경에 따라 어떤 스토리지 드라이버를 사용해야할지 다르다!

**스토리지 드라이버의 원리**

 컨테이너 내부에서 읽기, 쓰기 작업이 일어날 때는 드라이버에 따라 Copy-On-Write(COW) 또는 Redirect-On-Write(ROW) 개념을 사용한다.
- **스냅숏**: 원본 파일은 읽기 전용으로 사용하되 이 파일이 변경되면 새로운 공간을 할당한다. <u>파일을 불변상태로 유지할 수 있다!</u>
- 스토리지를 스냅숏으로 만들면 스냅숏 안에 어느 파일이 어디에 저장돼 있는지가 목록으로 저장된다. 이 스냅숏을 사용하다가 스냅숏 안의 파일에 변화가 생기면 변경된 내역을 따로 관리함으로써 스냅숏을 사용한다.

#### 컨테이너 저장 공간 설정
컨테이너 내부에서 사용되는 파일시스템의 크기는 도커가 사용하고 있는 스토리지 드라이버에 따라 조금씩 다르다.
- `--storage-opt` 옵션: 컨테이너를 생성할 때 이 옵션으로 저장공간을 제한할 수 있다.

### 2.5.4 도커 데몬 모니터링
#### 도커 데몬 디버그 모드
```
dockerd -D 
```
- 로컬 도커 클라이언트에서 오가는 모든 명령어를 로그로 출력한다.
- 단점: 원하지 않는 정보까지 많이 출력, 호스트에 있는 파일을 일거나 도커 데몬을 포그라운드 상태로 실행해야한다.

#### events, stats, system df 명령어

**events**
```
docker events
```
도커 데몬에 어떤 일이 일어나고 있는지 실시간 스트림 로그로 보여준다.
```
// 새로운 터미널
docker pull ubuntu:14.04
```
```
// 이벤트 로그 찍힘!
docker events
2021-10-04T00:42:03.189017396+09:00 image pull ubuntu:14.04 (name=ubuntu)
```
`filter` 옵션을 사용해 원하는 정보만 출력하도록 설정할 수 있다.
```
docker events --filter 'type=image' 
// 이미지 관련된 명령어만 출력
```

**stats**
```
docker stats
CONTAINER ID   NAME      CPU %     MEM USAGE / LIMIT   MEM %     NET I/O   BLOCK I/O   PIDS
```
- 실행중인 모든 컨테이너의 자원 사용량을 스트림으로 출력
- `--no-stream` 옵션으로 한번만 출력할 수 있다.

**system df**
```
docker system df
TYPE            TOTAL     ACTIVE    SIZE      RECLAIMABLE
Images          5         2         669.1MB   644.7MB (96%)
Containers      4         0         468B      468B (100%)
Local Volumes   0         0         0B        0B
Build Cache     62        0         405.3MB   405.3MB
```
- 도커에서 사용하고 있는 이미지, 컨테이너, 로컬 볼륨의 총 개수 및 사용 중인 개수, 크기, 삭제함으로써 확보 가능한 공간을 출력
- RECLAIMABLE: 사용중이지 않은 이미지를 삭제함으로써 확보할 수 있는 공간

**CAdivsor**
- 구글이 만든 컨테이너 모니터링 도구