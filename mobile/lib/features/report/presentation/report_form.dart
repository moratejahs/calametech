import 'dart:io';
import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/features/location/cubit/location_cubit.dart';
import 'package:calamitech/features/report/blocs/report_bloc.dart';
import 'package:calamitech/features/tips/blocs/tips_bloc.dart';
import 'package:calamitech/features/report/presentation/emergency_type_button.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:image_picker/image_picker.dart';

class ReportForm extends StatefulWidget {
  const ReportForm({super.key});

  @override
  State<ReportForm> createState() => _ReportFormState();
}

class _ReportFormState extends State<ReportForm> {
  String? selectedEmergencyType;
  final TextEditingController _descriptionController = TextEditingController();
  File? _imageFile;
  final ImagePicker _picker = ImagePicker();

  @override
  void dispose() {
    _descriptionController.dispose();
    super.dispose();
  }

  void _selectEmergencyType(String type) {
    setState(() {
      selectedEmergencyType = type;
    });
  }

  Future<void> _showImageSourceActionSheet() async {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(16.0)),
      ),
      builder: (_) {
        return SafeArea(
          child: Wrap(
            children: [
              ListTile(
                leading: const Icon(Icons.photo_camera),
                title: const Text('Take a Photo'),
                onTap: () async {
                  Navigator.of(context).pop();
                  final pickedFile =
                      await _picker.pickImage(source: ImageSource.camera);
                  if (pickedFile != null) {
                    setState(() {
                      _imageFile = File(pickedFile.path);
                    });
                  }
                },
              ),
              ListTile(
                leading: const Icon(Icons.photo_library),
                title: const Text('Choose from Gallery'),
                onTap: () async {
                  Navigator.of(context).pop();
                  final pickedFile =
                      await _picker.pickImage(source: ImageSource.gallery);
                  if (pickedFile != null) {
                    setState(() {
                      _imageFile = File(pickedFile.path);
                    });
                  }
                },
              ),
            ],
          ),
        );
      },
    );
  }

  void _submit(BuildContext context) {
    if (selectedEmergencyType == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please select an emergency type.'),
          backgroundColor: Colors.red,
          behavior: SnackBarBehavior.floating,
        ),
      );
      return;
    }

    if (_descriptionController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please describe the situation.'),
          backgroundColor: Colors.red,
          behavior: SnackBarBehavior.floating,
        ),
      );
      return;
    }

    final locationState = context.read<LocationCubit>().state;
    context.read<ReportBloc>().add(
          ReportSubmitted(
            emergencyType: selectedEmergencyType!,
            description: _descriptionController.text,
            image: _imageFile,
            lat: locationState.latitude.toString(),
            long: locationState.longitude.toString(),
          ),
        );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<ReportBloc, ReportState>(
      listener: (context, state) async {
        if (state is ReportSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: Colors.green,
              duration: const Duration(seconds: 5),
              behavior: SnackBarBehavior.floating,
            ),
          );

          await _showTipsDialogAfterSubmit();

          setState(() {
            selectedEmergencyType = null;
            _descriptionController.clear();
            _imageFile = null;
          });
        } else if (state is ReportFailure) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: Colors.red,
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
      },
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'What kind of Emergency?',
                style: TextStyle(color: Colors.red, fontSize: 18.0),
              ),
              const SizedBox(height: 8.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  EmergencyTypeButton(
                    image: AssetPaths.fire,
                    title: 'Fire',
                    isSelected: selectedEmergencyType == 'fire',
                    onTap: () => _selectEmergencyType('fire'),
                  ),
                  const SizedBox(width: 16.0),
                  EmergencyTypeButton(
                    image: AssetPaths.home,
                    title: 'Flood',
                    isSelected: selectedEmergencyType == 'flood',
                    onTap: () => _selectEmergencyType('flood'),
                  ),
                ],
              ),
              const SizedBox(height: 16.0),
              const Text(
                'Describe the situation',
                style: TextStyle(color: Colors.red, fontSize: 18.0),
              ),
              const SizedBox(height: 8.0),
              TextField(
                controller: _descriptionController,
                maxLines: 5,
                decoration: const InputDecoration(
                  hintText: 'Type here...',
                  contentPadding: EdgeInsets.all(16.0),
                  border: InputBorder.none,
                ),
              ),
              const SizedBox(height: 16.0),
              const Text(
                'Upload an image (Optional)',
                style: TextStyle(color: Colors.red, fontSize: 18.0),
              ),
              const SizedBox(height: 8.0),
              GestureDetector(
                onTap: _showImageSourceActionSheet,
                child: Container(
                  width: double.infinity,
                  height: 250.0,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(8.0),
                    border: Border.all(color: Colors.grey.shade300, width: 1.0),
                  ),
                  child: _imageFile != null
                      ? Image.file(
                          _imageFile!,
                          fit: BoxFit.cover,
                        )
                      : const Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              Icons.cloud_upload,
                              size: 40.0,
                              color: Colors.grey,
                            ),
                            Text(
                              'Tap to upload an image (6mb max)',
                              style: TextStyle(color: Colors.grey),
                            ),
                          ],
                        ),
                ),
              ),
              const SizedBox(height: 16.0),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  style: ButtonStyle(
                    backgroundColor:
                        MaterialStateProperty.all<Color>(Colors.red),
                  ),
                  onPressed: () => _submit(context),
                  child: BlocBuilder<ReportBloc, ReportState>(
                    builder: (context, state) {
                      return state is ReportLoading
                          ? const CircularProgressIndicator(color: Colors.white)
                          : const Text('Submit Report');
                    },
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _showTipsDialogAfterSubmit() async {
    // Dispatch event to fetch tips based on description
    try {
      context
          .read<TipsBloc>()
          .add(TipsFetched(description: _descriptionController.text));
    } catch (_) {
      // ignore: avoid_print
      print('TipsBloc not available in context');
    }

    // Show dialog that listens to TipsBloc states
    await showDialog<void>(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return BlocProvider.value(
          value: context.read<TipsBloc>(),
          child: AlertDialog(
            title: const Text('AI-generated tips'),
            content: SizedBox(
              width: double.maxFinite,
              child: BlocBuilder<TipsBloc, TipsState>(
                builder: (context, state) {
                  if (state is TipsLoading) {
                    return const Center(child: CircularProgressIndicator());
                  }

                  if (state is TipsLoaded) {
                    final tips = state.tips;
                    return ListView.separated(
                      shrinkWrap: true,
                      itemCount: tips.length,
                      separatorBuilder: (_, __) => const Divider(),
                      itemBuilder: (context, index) {
                        final tip = tips[index];
                        return ListTile(
                          leading: CircleAvatar(child: Text('#${index + 1}')),
                          title: Text(tip.content),
                          subtitle: Text('Type: ${tip.type}'),
                        );
                      },
                    );
                  }

                  if (state is TipsFailure) {
                    return Center(child: Text(state.message));
                  }

                  return const SizedBox.shrink();
                },
              ),
            ),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop();
                  Navigator.pushNamed(
                      context,
                      selectedEmergencyType == 'fire'
                          ? AppRoutes.fireTips
                          : AppRoutes.floodTips);
                },
                child: const Text('Continue'),
              ),
            ],
          ),
        );
      },
    );
  }
}
