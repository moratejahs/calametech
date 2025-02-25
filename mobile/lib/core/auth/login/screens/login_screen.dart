import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _loginFormKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    final TextEditingController emailController = TextEditingController();
    final TextEditingController passwordController = TextEditingController();

    return BlocListener<LoginBloc, LoginState>(
      listener: (context, state) {
        if (state is LoginFailure) {
          if (state.message != null && state.message!.isNotEmpty) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(state.message ?? 'An error occurred, please try again.'),
                backgroundColor: Colors.red,
              ),
            );
          }

          passwordController.clear();
        }

        if (state is LoginSuccess) {
          context.go(RouteConstants.home);
        }
      },
      child: Scaffold(
          body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [AppTheme.primaryColor, Colors.blue[50]!],
          ),
        ),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Center(
            child: Form(
              key: _loginFormKey,
              child: Column(
                spacing: 10,
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Center(
                    child: Image.asset(
                      AssetPaths.appLogo,
                      width: 150,
                      height: 150,
                      fit: BoxFit.contain,
                    ),
                  ),
                  const Text(
                    'Sign in to your Account',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),

                  // Email field
                  BlocBuilder<LoginBloc, LoginState>(
                    builder: (context, state) {
                      String? emailError;
                      if (state is LoginFailure && state.errors?['email'] != null) {
                        emailError = state.errors!['email'][0];
                      }

                      return TextFormField(
                        controller: emailController,
                        keyboardType: TextInputType.emailAddress,
                        decoration: InputDecoration(
                          labelText: 'Email',
                          errorText: emailError,
                        ),
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return 'Please enter your email';
                          }

                          if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(value)) {
                            return 'Please enter a valid email';
                          }

                          return null;
                        },
                      );
                    },
                  ),

                  // Password field
                  BlocBuilder<LoginBloc, LoginState>(
                    builder: (context, state) {
                      String? passwordError;
                      if (state is LoginFailure && state.errors?['password'] != null) {
                        passwordError = state.errors!['password'][0];
                      }

                      return TextFormField(
                        controller: passwordController,
                        obscureText: true,
                        decoration: InputDecoration(
                          labelText: 'Password',
                          errorText: passwordError,
                          border: const OutlineInputBorder(),
                        ),
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return 'Please enter your password';
                          }
                          return null;
                        },
                      );
                    },
                  ),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {
                        if (_loginFormKey.currentState?.validate() ?? false) {
                          context.read<LoginBloc>().add(LoginButtonPressed(
                                email: emailController.text,
                                password: passwordController.text,
                              ));
                          FocusManager.instance.primaryFocus?.unfocus();
                        }
                      },
                      child: BlocBuilder<LoginBloc, LoginState>(
                        builder: (context, state) {
                          if (state is LoginLoading) {
                            return const CircularProgressIndicator(
                              valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                            );
                          }
                          return const Text('Sign In');
                        },
                      ),
                    ),
                  ),
                  const SizedBox(height: 6),
                  Row(
                    spacing: 4,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text('Don\'t have an account?'),
                      GestureDetector(
                        onTap: () {
                          GoRouter.of(context).push(RouteConstants.signup);
                        },
                        child: Text(
                          'Sign up',
                          style: TextStyle(color: AppTheme.primaryColor),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      )),
    );
  }
}
