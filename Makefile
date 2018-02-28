.PHONY: install uninstall start stop

IMAGE = vending_machine_image
CONTAINER = vending_machine

install:
	@ docker build --no-cache -t $(IMAGE) .

uninstall:
	@ docker rmi $(IMAGE)

start:
	@ chmod -R 0777 db
	@ docker run --rm -d --name $(CONTAINER) -p 8080:8080 -v $(PWD):/var/www $(IMAGE)

stop:
	@ docker stop $(CONTAINER)

