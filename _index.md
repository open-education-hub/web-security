---
linkTitle: Web Security
type: docs
menu:
  main:
    weight: 20
---

# SSS Web Security

This is the public repository for the **Web Security** track of the Security Summer School (SSS).
It contains information available to participants: session content, slides, activities support files.
Apart from SSS participants, you can use this content by yourself to learn more about **Web Security**.

## Using the Content

As with all SSS tracks, the **Web Security** track consists of multiple sessions.
One session typically covers a particular topic of the track.
Ideally you would go through the sessions orderly, but you can jump ahead to a topic of interest.

Each session has its own folder.
The `index.md` file from a session folder details the topic of the session and includes practical demos of tools and snippets of code.

After reading the session contents, jump to the hands-on activities.
A typical activity will challenge you to solve / exploit a given issue and obtain a flag.
Read the activity description in the session contents and then use the activity support file to get to a solution.
Each activity will develop your practical skills and help you get a better understanding of the session topics.

Activities come in two forms:

* **tutorials**: that provide a step-by-step detailing of the solution.
* **challenges**: that only state the goal and provide an initial skeleton (and maybe some hints), then the rest is up to you.
  Even so, challenge activity folders store the reference solution for you to take a sneak peak in case you're lost.

## Support

In case you have questions or want to know more about a topic, use the the `Discussions` tab or join the [SSS Discord community](https://discord.gg/qx3RmNEVkB).

### Session Folder Structure

A session folder consists of:

* `index.md`: session contents, written in [GitHub Markdown](https://guides.github.com/features/mastering-markdown/)
* `activities/`: each activity has its own folder with support files (including the reference solution)
* `assets/`: support files for session contents (images, diagrams, code snippets, packet captures, etc.)
* `slides/`: session slides

### Activity Folder Structure

An activity folder consists of:

* `public/`: support file for the activity: binary files, helper scripts, packet captures etc.
* `sol/`: the reference solution of the activity.
  Tutorial activities feature a `README.md` file in the solution folder, with a detailing of the solution.

## Contributing

Contributions are welcome.
See the [contribution guide](CONTRIBUTING.md) on how you could report or fix issues and on how you can improve the content.

Reviewers are requested to follow the [reviewing guide](REVIEWING.md).

For in-depth discussions, please join the [SSS Discord community](https://discord.gg/qx3RmNEVkB).
