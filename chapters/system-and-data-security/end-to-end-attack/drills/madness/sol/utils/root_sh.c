//  SPDX-License-Identifier: BSD-3-Clause
// Copyright 2024 Catalin Iovita
#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>
#include <stdlib.h>

int main(void)
{
	// set user as root;
	setuid(0);
	setgid(0);
	seteuid(0);
	setegid(0);

	// spawn a shell;
	system("/bin/bash");
}
