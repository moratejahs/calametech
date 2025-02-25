import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/signup/bloc/signup_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class SignupScreen extends StatefulWidget {
  const SignupScreen({super.key});

  @override
  State<SignupScreen> createState() => _SignupScreenState();
}

class _SignupScreenState extends State<SignupScreen> {
  final _signinFormKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    final TextEditingController nameController = TextEditingController();
    final TextEditingController emailController = TextEditingController();
    final TextEditingController passwordController = TextEditingController();
    final TextEditingController passwordConfirmationController = TextEditingController();

    return BlocListener<SignupBloc, SignupState>(
      listener: (context, state) {
        if (state is SignupSuccess) {
          nameController.clear();
          emailController.clear();
          passwordController.clear();
          passwordConfirmationController.clear();

          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: Colors.green,
            ),
          );
        }
      },
      child: Scaffold(
          appBar: AppBar(
            backgroundColor: AppTheme.primaryColor,
            elevation: 0,
          ),
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
                child: SingleChildScrollView(
                  child: Form(
                    key: _signinFormKey,
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
                          'Create your Account',
                          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                        ),
                        // Password field
                        BlocBuilder<SignupBloc, SignupState>(
                          builder: (context, state) {
                            String? nameError;
                            if (state is SignupFailure && state.errors?['name'] != null) {
                              nameError = state.errors!['name'][0];
                            }
                  
                            return TextFormField(
                              controller: nameController,
                              decoration: InputDecoration(
                                labelText: 'Name',
                                errorText: nameError,
                                border: const OutlineInputBorder(),
                              ),
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Please enter your name';
                                }
                                return null;
                              },
                            );
                          },
                        ),
                  
                        // Email field
                        BlocBuilder<SignupBloc, SignupState>(
                          builder: (context, state) {
                            String? emailError;
                            if (state is SignupFailure && state.errors?['email'] != null) {
                              emailError = state.errors!['email'][0];
                            }
                  
                            return TextFormField(
                              controller: emailController,
                              keyboardType: TextInputType.emailAddress,
                              decoration: InputDecoration(
                                labelText: 'Email',
                                errorText: emailError, // Display Laravel email error here
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
                        BlocBuilder<SignupBloc, SignupState>(
                          builder: (context, state) {
                            String? passwordError;
                            if (state is SignupFailure && state.errors?['password'] != null) {
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
                  
                        // Password confirmation field
                        BlocBuilder<SignupBloc, SignupState>(
                          builder: (context, state) {
                            String? passwordConfirmationError;
                            if (state is SignupFailure && state.errors?['password_confirmation'] != null) {
                              passwordConfirmationError = state.errors!['password_confirmation'][0];
                            }
                  
                            return TextFormField(
                              controller: passwordConfirmationController,
                              obscureText: true,
                              decoration: InputDecoration(
                                labelText: 'Password Confirmation',
                                errorText: passwordConfirmationError,
                                border: const OutlineInputBorder(),
                              ),
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Please enter your password confirmation';
                                }
                                return null;
                              },
                            );
                          },
                        ),
                  
                        // Sign up button
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: () {
                              if (_signinFormKey.currentState?.validate() ?? false) {
                                context.read<SignupBloc>().add(SignupRequested(
                                      name: nameController.text,
                                      email: emailController.text,
                                      password: passwordController.text,
                                      confirmPassword: passwordConfirmationController.text,
                                    ));
                                FocusScope.of(context).unfocus();
                              }
                            },
                            child: BlocBuilder<SignupBloc, SignupState>(
                              builder: (context, state) {
                                if (state is SignupLoading) {
                                  return const CircularProgressIndicator(
                                    valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                                  );
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
                                GoRouter.of(context).pop(RouteConstants.login);
                              },
                              child: Text(
                                'Sign in',
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
            ),
          )),
    );
  }
}
