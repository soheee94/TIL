시작하세요! 도커 / 쿠버네티스 

### 쿠버네티스 시작

쿠버네티스는 오늘날 사실상 표준으로 사용되고 있는 컨테이너 오케스트레이션 도구이다.
- **컨테이너 오케스트레이션?** 컨테이너의 배포, 관리, 확장, 네트워킹을 자동화하는것
- 쿠버네티스의 장점
    - 컨테이너 기반의 서비스 운영에 필요한 대부분의 오케스트레이션 기능을 폭넓게 지원한다.
    - 구글, 레드햇을 비롯한 많은 오픈소스 진영에서 쿠버네티스의 소스코드에 기여하고 있기 때문에 성능과 안전성 면에서 신뢰할 수 있다.
    - 컨테이너 기반의 클라우드를 운영할 때 필요한 대부분의 기능과 컴포넌트를 사용자가 직접 커스터마이징 할 수 있다.
    - CNCF(Cloud Native Computing Foundation) 및 다른 클라우드 운영도구 들과 쉽게 연동되므로 확장성이 높다.

# 05. 쿠버네티스 설치
## 5.1 쿠버네티스 설치 환경의 종류
- 개발 용도의 쿠버네티스: Minikube, Docker Desktop for Mac/Windows 에 내장된 쿠버네티스
- 서비스, 운영 용도의 쿠버네티스
    - 자체 서버 환경에서서 쿠버네티스 설치
    - 클라우드 플랫폼에서 쿠버네티스 설치
    - 쿠버네티스 자체를 클라우드 서비스로서 사용
    
## 5.2 쿠버네티스 버전 선택
쿠버네티스의 기능이 매우 빠르게 업데이트 되기 때문에 사소한 버전 차이로 인해 쿠버네티스의 사용 방법이나 기능이 달라질 수 있다. 지금은 <u>'쿠버네티스를 설치할 때는 너무 최신 버전이거나 너무 예전 버전을 사용하지 않는 것이 좋다'</u> 라는것만 기억하자.

## 5.3 개발 용도의 쿠버네티스 설치
#### Docker Desktop for Mac 에서 쿠버네티스 사용
[Preferences] -> [Kubernetes] -> Enable Kubernetes 체크박스 체크
```
hansohee@SoHee-MacBookPro ~ % kubectl version --short
Client Version: v1.21.4
Server Version: v1.21.4
```
Docker Desktop 에서는 일부 네트워크, 볼륨 기능이 제대로 동작하지 않을 수 있다. 따라서 테스트 용도로 가볍게 사용하자.


[AKS(Azure Kubernetes Service)](https://azure.microsoft.com/ko-kr/services/kubernetes-service/?&ef_id=Cj0KCQjwnoqLBhD4ARIsAL5JedIOev82ryDiJhJarAlMy4ELVxchGByQZdczSOEBCXsa8P16egyA2P0aAmivEALw_wcB:G:s&OCID=AID2200210_SEM_Cj0KCQjwnoqLBhD4ARIsAL5JedIOev82ryDiJhJarAlMy4ELVxchGByQZdczSOEBCXsa8P16egyA2P0aAmivEALw_wcB:G:s&gclid=Cj0KCQjwnoqLBhD4ARIsAL5JedIOev82ryDiJhJarAlMy4ELVxchGByQZdczSOEBCXsa8P16egyA2P0aAmivEALw_wcB#overview)
