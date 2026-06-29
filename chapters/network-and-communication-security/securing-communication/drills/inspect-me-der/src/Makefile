.PHONY: all clean

all: public.tar

public.tar: example.der
	tar cf $@ $^

example.der:
	./generate_self_signed_cert.sh

clean:
	-rm -f example.key example.der
	-rm -f public.tar
	-rm -f *~
