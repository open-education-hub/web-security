.PHONY: all clean

all: public.tar

public.tar: example.crt
	tar cf $@ $^

example.crt:
	./generate_self_signed_cert.sh

clean:
	-rm -f example.key example.crt
	-rm -f public.tar
	-rm -f *~
