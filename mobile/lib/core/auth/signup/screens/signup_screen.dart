import 'package:calametech/constants/route_constants.dart';
import 'package:calametech/core/auth/signup/bloc/signup_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class SignupScreen extends StatelessWidget {
  const SignupScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final TextEditingController nameController = TextEditingController();
    final TextEditingController emailController = TextEditingController();
    final TextEditingController passwordController = TextEditingController();
    final TextEditingController passwordConfirmationController = TextEditingController();

    return BlocListener<SignupBloc, SignupState>(
      listener: (context, state) {
        if (state is SignupSuccess) {
          context.go(RouteConstants.home);
        }
      },
      child: Scaffold(
          body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextField(
                controller: emailController,
                decoration: const InputDecoration(
                  labelText: 'Name',
                  border: OutlineInputBorder(),
                ),
              ),
              BlocBuilder<SignupBloc, SignupState>(
                builder: (context, state) {
                  if (state is SignupFailure) {
                    return const Padding(
                      padding: EdgeInsets.only(top: 6.0),
                      child: Text(
                        // (state.message ?? state.errors?['email'][0]).toString(),
                        'wow',
                        style: TextStyle(fontSize: 14, color: Colors.red),
                      ),
                    );
                  }
                  return const SizedBox();
                },
              ),
              const SizedBox(height: 16),
              TextField(
                controller: emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  border: OutlineInputBorder(),
                ),
              ),
              BlocBuilder<SignupBloc, SignupState>(
                builder: (context, state) {
                  if (state is SignupFailure) {
                    return const Padding(
                      padding: EdgeInsets.only(top: 6.0),
                      child: Text(
                        // (state.message ?? state.errors?['email'][0]).toString(),
                        'wow',
                        style: TextStyle(fontSize: 14, color: Colors.red),
                      ),
                    );
                  }
                  return const SizedBox();
                },
              ),
              const SizedBox(height: 16),
              TextField(
                controller: passwordController,
                decoration: const InputDecoration(
                  labelText: 'Password',
                  border: OutlineInputBorder(),
                ),
                obscureText: true,
              ),
              BlocBuilder<SignupBloc, SignupState>(
                builder: (context, state) {
                  if (state is SignupFailure) {
                    return const Padding(
                      padding: EdgeInsets.only(top: 6.0),
                      child: Text(
                        // (state.errors?['password'][0]).toString(),
                        'wow',
                        style: TextStyle(fontSize: 14, color: Colors.red),
                      ),
                    );
                  }
                  return const SizedBox();
                },
              ),
              const SizedBox(height: 16),
              TextField(
                controller: passwordController,
                decoration: const InputDecoration(
                  labelText: 'Password Confirmation',
                  border: OutlineInputBorder(),
                ),
                obscureText: true,
              ),
              BlocBuilder<SignupBloc, SignupState>(
                builder: (context, state) {
                  if (state is SignupFailure) {
                    return const Padding(
                      padding: EdgeInsets.only(top: 6.0),
                      child: Text(
                        // (state.errors?['password'][0]).toString(),
                        'wow',
                        style: TextStyle(fontSize: 14, color: Colors.red),
                      ),
                    );
                  }
                  return const SizedBox();
                },
              ),
              const SizedBox(height: 20),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    context.read<SignupBloc>().add(SignupRequested(
                        name: nameController.text,
                        email: emailController.text,
                        password: passwordController.text,
                        confirmPassword: passwordConfirmationController.text));
                  },
                  child: BlocBuilder<SignupBloc, SignupState>(
                    builder: (context, state) {
                      if (state is SignupLoading) {
                        return const CircularProgressIndicator();
                      }
                      return const Text('Sign Up');
                    },
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text('Have an account?'),
                  const SizedBox(width: 4),
                  GestureDetector(
                    onTap: () {
                      context.go(RouteConstants.login);
                    },
                    child: const Text(
                      'Sign in',
                      style: TextStyle(color: Colors.blue),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      )),
    );
  }
}
