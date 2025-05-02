import 'dart:io';

import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/features/auth/blocs/auth_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:image_picker/image_picker.dart';

class RegisterForm extends StatefulWidget {
  const RegisterForm({super.key});

  @override
  State<RegisterForm> createState() => _RegisterFormState();
}

class _RegisterFormState extends State<RegisterForm> {
  final loginFormKey = GlobalKey<FormState>();
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  final passwordConfirmationController = TextEditingController();
  final phoneController = TextEditingController();
  final addressController = TextEditingController();
  final idTypeController = TextEditingController();
  File? avatar;
  File? idPicture;

  String? avatarError;
  String? idPictureError;

  final ImagePicker picker = ImagePicker();

  Future<void> pickAvatar() async {
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      setState(() {
        avatar = File(pickedFile.path);
        avatarError = null;
      });
    }
  }

  Future<void> pickIdPicture() async {
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      setState(() {
        idPicture = File(pickedFile.path);
        idPictureError = null;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<AuthBloc, AuthState>(
      listener: (context, state) {
        if (state is AuthFailure) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: Colors.red,
              behavior: SnackBarBehavior.floating,
            ),
          );
        } else if (state is AuthRegisterFieldError) {
          if (state.passwordError != null || state.passwordConfirmationError != null) {
            passwordController.clear();
            passwordConfirmationController.clear();
          }
        } else if (state is AuthAuthenticated) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Logged in.'),
              backgroundColor: Colors.green,
              behavior: SnackBarBehavior.floating,
            ),
          );
          Navigator.pushNamedAndRemoveUntil(
            context,
            AppRoutes.home,
            (route) => false,
          );
        }
      },
      builder: (context, state) {
        return Form(
          key: loginFormKey,
          child: Column(
            spacing: 10,
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Avatar Field
              GestureDetector(
                  onTap: pickAvatar,
                  child: Container(
                    width: 120.0,
                    height: 120.0,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(60),
                      border: Border.all(color: avatarError == null ? Colors.white : Colors.red, width: 1.0),
                    ),
                    child: avatar != null
                        ? ClipRRect(
                            borderRadius: BorderRadius.circular(60), // Match the container's border radius
                            child: Image.file(
                              avatar!,
                              fit: BoxFit.cover, // Ensures the image fills the container without overflow
                              width: 120.0,
                              height: 120.0,
                            ),
                          )
                        : const Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.camera_alt,
                                size: 30.0,
                                color: Colors.white,
                              ),
                            ],
                          ),
                  )),
              if (avatarError != null)
                Text(
                  avatarError!,
                  style: const TextStyle(color: Colors.red),
                ),
              if (state is AuthRegisterFieldError && state.avatarError != null)
                Text(
                  state.avatarError!,
                  style: const TextStyle(color: Colors.red),
                ),
              const SizedBox(height: 10),

              // Name Field
              TextFormField(
                controller: nameController,
                decoration: InputDecoration(
                  labelText: 'Name',
                  hintText: 'ex. Juan Cruz',
                  errorText: state is AuthRegisterFieldError && state.nameError != null ? state.nameError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your name';
                  }

                  return null;
                },
              ),

              // Email Field
              TextFormField(
                controller: emailController,
                keyboardType: TextInputType.emailAddress,
                decoration: InputDecoration(
                  labelText: 'Email',
                  hintText: 'ex. juan@example.com',
                  errorText: state is AuthRegisterFieldError && state.emailError != null ? state.emailError : null,
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
              ),

              // Password Field
              TextFormField(
                controller: passwordController,
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Password',
                  errorText: state is AuthRegisterFieldError && state.passwordError != null ? state.passwordError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your password';
                  }
                  return null;
                },
              ),

              // Password Confirmation Field
              TextFormField(
                controller: passwordConfirmationController,
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Password Confirmation',
                  errorText: state is AuthRegisterFieldError && state.passwordConfirmationError != null ? state.passwordConfirmationError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your password again';
                  }
                  return null;
                },
              ),

              // Phone Field
              TextFormField(
                controller: phoneController,
                keyboardType: TextInputType.phone,
                decoration: InputDecoration(
                  labelText: 'Contact Number',
                  hintText: 'ex. 09123456789',
                  errorText: state is AuthRegisterFieldError && state.phoneError != null ? state.phoneError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your phone';
                  }

                  return null;
                },
              ),

              // Address Field
              TextFormField(
                controller: addressController,
                decoration: InputDecoration(
                  labelText: 'Address',
                  hintText: 'Purok, Barangay, City, Province',
                  errorText: state is AuthRegisterFieldError && state.addressError != null ? state.addressError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your address';
                  }

                  return null;
                },
              ),

              // ID Type Field
              DropdownButtonFormField<String>(
                value: idTypeController.text.isNotEmpty ? idTypeController.text : null,
                items: const [
                  DropdownMenuItem(value: 'National ID', child: Text('National ID')),
                  DropdownMenuItem(value: 'Driver\'s License ID', child: Text('Driver\'s License ID')),
                  DropdownMenuItem(value: 'Postal ID', child: Text('Postal ID')),
                  DropdownMenuItem(value: 'School ID', child: Text('School ID')),
                  DropdownMenuItem(value: 'Others', child: Text('Others')),
                ],
                onChanged: (value) {
                  idTypeController.text = value ?? '';
                },
                decoration: InputDecoration(
                  labelText: 'ID Type',
                  hintText: 'Select ID Type',
                  errorText: state is AuthRegisterFieldError && state.idTypeError != null ? state.idTypeError : null,
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please select your ID type';
                  }
                  return null;
                },
              ),

              // ID Picture Field
              GestureDetector(
                onTap: () => pickIdPicture(),
                child: Container(
                  height: 250,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    border: Border.all(color: avatarError == null ? Colors.white : Colors.red),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: idPicture != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(8), // Match the container's border radius
                          child: Image.file(
                            idPicture!,
                            fit: BoxFit.cover, // Ensures the image fills the container without overflow
                            width: double.infinity,
                            height: 250,
                          ),
                        )
                      : const Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.camera_alt,
                                size: 50.0,
                                color: Colors.white,
                              ),
                              Text(
                                'Tap to upload ID picture (6mb max)',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 16,
                                ),
                              ),
                            ],
                          ),
                        ),
                ),
              ),
              if (idPictureError != null)
                Text(
                  idPictureError!,
                  style: const TextStyle(color: Colors.red),
                ),
              if (state is AuthRegisterFieldError && state.idPictureError != null)
                Text(
                  state.idPictureError!,
                  style: const TextStyle(color: Colors.red),
                ),
              const SizedBox(height: 10),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    setState(() {
                      avatarError = avatar == null ? 'Please upload an avatar.' : null;
                      idPictureError = idPicture == null ? 'Please upload an ID picture.' : null;
                    });

                    final isFormValid = loginFormKey.currentState?.validate() ?? false;

                    if (isFormValid && avatar != null && idPicture != null) {
                      context.read<AuthBloc>().add(
                            RegisterRequested(
                              name: nameController.text,
                              email: emailController.text,
                              password: passwordController.text,
                              passwordConfirmation: passwordConfirmationController.text,
                              phone: phoneController.text,
                              address: addressController.text,
                              avatar: avatar!,
                              idPicture: idPicture!,
                              idType: idTypeController.text,
                            ),
                          );
                      FocusManager.instance.primaryFocus?.unfocus();
                    }
                  },
                  child: state is AuthLoading
                      ? const CircularProgressIndicator(
                          color: Colors.white,
                        )
                      : const Text(
                          'Sign in',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 18,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                ),
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text('Already have an account?'),
                  TextButton(
                    onPressed: () {
                      Navigator.pop(context);
                    },
                    child: const Text('Sign in'),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
